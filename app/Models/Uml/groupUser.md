# Group CRUD + Welcome email (WorkOS OIDC)

Ce document contient plusieurs fichiers exemples prêts à être collés dans votre projet Laravel pour :

* Controller resource pour gérer les groupes (CRUD) + assignation/suppression d'utilisateurs.
* Mailable pour l'envoi d'un email de bienvenue après création de compte.
* Observer `UserObserver` qui envoie cet email quand un `User` est créé.
* Extraits de routes et d'une méthode de callback OIDC (WorkOS) montrant comment créer l'utilisateur.
* Vue Blade pour l'email.

---

## `app/Http/Controllers/Access/GroupController.php`

```php
<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Models\Access\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::withCount('users')->paginate(15);
        if ($request->wantsJson()) {
            return response()->json($groups);
        }
        return view('access.groups.index', compact('groups'));
    }

    public function create()
    {
        return view('access.groups.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name',
            'description' => 'nullable|string',
            'role_id' => 'nullable|exists:roles,id',
            'is_active' => 'sometimes|boolean',
        ]);

        $group = Group::create($data);

        return redirect()->route('groups.show', $group)->with('success', 'Groupe créé');
    }

    public function show(Group $group)
    {
        $group->load('users');
        return view('access.groups.show', compact('group'));
    }

    public function edit(Group $group)
    {
        return view('access.groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name,' . $group->id,
            'description' => 'nullable|string',
            'role_id' => 'nullable|exists:roles,id',
            'is_active' => 'sometimes|boolean',
        ]);

        $group->update($data);

        return redirect()->route('groups.show', $group)->with('success', 'Groupe mis à jour');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Groupe supprimé');
    }

    /**
     * Assign a user to the group (AJAX or form POST).
     */
    public function assignUser(Request $request, Group $group)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // prevent duplicates
        $group->users()->syncWithoutDetaching([$data['user_id']]);

        return redirect()->back()->with('success', 'Utilisateur attribué au groupe');
    }

    /**
     * Remove (detach) a user from the group.
     */
    public function removeUser(Request $request, Group $group, User $user)
    {
        $group->users()->detach($user->id);

        return redirect()->back()->with('success', 'Utilisateur retiré du groupe');
    }
}
```

---

## `app/Mail/WelcomeUser.php`

```php
<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeUser extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Bienvenue sur ' . config('app.name'))
                    ->view('emails.welcome')
                    ->with(['user' => $this->user]);
    }
}
```

---

## `app/Observers/UserObserver.php`

```php
<?php

namespace App\Observers;

use App\Mail\WelcomeUser;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    public function created(User $user)
    {
        // N'envoyer l'email que si l'utilisateur a une adresse email valide
        if ($user->email) {
            // On peut utiliser ->queue() si vous avez configuré les queues
            Mail::to($user->email)->send(new WelcomeUser($user));
        }
    }
}
```

---

## `routes/web.php` (extraits)

```php
use App\Http\Controllers\Access\GroupController;

Route::middleware(['auth'])->group(function () {
    Route::resource('groups', GroupController::class);

    // Routes additionnelles pour assign/detach
    Route::post('groups/{group}/assign-user', [GroupController::class, 'assignUser'])->name('groups.assign-user');
    Route::delete('groups/{group}/users/{user}', [GroupController::class, 'removeUser'])->name('groups.remove-user');
});
```

---

## `resources/views/emails/welcome.blade.php`

```blade
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h1>Bienvenue, {{ $user->name }} !</h1>
    <p>Merci d'avoir rejoint {{ config('app.name') }}.</p>
    <p>Pour commencer, vous pouvez vous connecter ici : <a href="{{ config('app.url') }}">{{ config('app.url') }}</a></p>
</body>
</html>
```

---

## Enregistrement de l'Observer

Ajoutez dans `App\Providers\AppServiceProvider::boot()` (ou `EventServiceProvider`) :

```php
use App\Models\User;
use App\Observers\UserObserver;

public function boot()
{
    User::observe(UserObserver::class);
}
```

Puis `composer dump-autoload` si nécessaire.

---

## Exemple de callback OIDC (WorkOS) — `app/Http/Controllers/Auth/WorkOSController.php` (snippet)

```php
public function callback(Request $request)
{
    // Exemple simplifié : récupérer les claims envoyés par WorkOS après OIDC
    // Vous avez normalement une lib WorkOS ou la vérification du token.
    $email = $request->input('email');
    $name = $request->input('name');
    $workosId = $request->input('sub'); // ou l'attribut qui contient l'ID WorkOS

    $user = \App\Models\User::firstOrCreate(
        ['workos_id' => $workosId],
        ['email' => $email, 'name' => $name]
    );

    // Si vous voulez vous assurer que l'utilisateur a bien été créé (observer enverra l'email)

    auth()->login($user);

    return redirect()->intended('/home');
}
```

> **Remarque** : l'observer `created` enverra l'email automatiquement quand `User::create()` ou `firstOrCreate()` crée un nouvel enregistrement.

---

## Notes / Suggestions

* Pensez à utiliser `syncWithoutDetaching()` pour ne pas écraser d'autres relations quand vous attribuez des utilisateurs.
* Si vous envoyez beaucoup d'emails, utilisez `Mail::to(...)->queue(new WelcomeUser($user))` et configurez une queue worker.
* Ajoutez des politiques (`Gate`/`Policy`) pour restreindre qui peut créer/modifier/destructer les groupes.
* Tests : écrivez des tests de feature pour `GroupController` et un test d'observer (faux pour Mail::fake()).

---

Si vous souhaitez que je crée aussi les views Blade (index/show/form), les Requests formalisées (`StoreGroupRequest`), ou une API resource (JSON) complète — je peux les ajouter directement.
