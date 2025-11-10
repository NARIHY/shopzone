<?php

namespace App\Http\Controllers\Access\Role;

use App\Common\RoleToPermissionView;
use App\Http\Controllers\Controller;
use App\Http\Requests\Access\Role\RolePermission\RoleToPermissionUpdateRequest;
use App\Models\Access\Role;
use App\Models\Access\Permission;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class RoleToPermissionController extends Controller
{
    /**
     * Affiche la liste des permissions pour un rôle donné.
     */
    public function index(Role $role)
    {
        // 🧠 Cache global des permissions (collection brute)
        $allPermissions = Cache::rememberForever('permissions_all', function () {
            return Permission::orderBy('name')->get(['id', 'name', 'description', 'is_active']);
        });

        // Pagination "manuelle" sur la collection
        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage(); // récupère ?page=X
        $permissions = new LengthAwarePaginator(
            $allPermissions->forPage($currentPage, $perPage), // items pour la page
            $allPermissions->count(), // total items
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),          // conserve le chemin actuel
                'query' => request()->query()        // conserve les query params (page=2, etc.)
            ]
        );

        // Permissions déjà assignées au rôle
        $assignedPermissions = Cache::remember("role_{$role->id}_permissions", 600, function () use ($role) {
            return $role->permissions()->pluck('permissions.id')->toArray();
        });

        // Préparer la vue
        $view = view(
            RoleToPermissionView::getListRoleToPermissionView(),
            compact('role', 'permissions', 'assignedPermissions')
        );

        // Libération mémoire
        unset($allPermissions, $permissions, $assignedPermissions);

        return $view;
    }

    /**
     * Met à jour les permissions d’un rôle.
     */
    public function update(RoleToPermissionUpdateRequest $request, Role $role)
    {
        try {
            $validated = $request->validated();

            // 🔄 Synchronise les permissions cochées
            $role->permissions()->sync($validated['permissions'] ?? []);

            // 🧹 Invalide les caches concernés
            Cache::forget("role_{$role->id}_permissions");

            // Optionnel : si tu veux forcer la régénération globale
            // Cache::forget('permissions_all');


            return redirect()
                ->route('admin.roleToPermission.index', $role->id)
                ->with('success', __('Permissions updated successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('warning', 'There was an error during the request. Reason: ' . $e->getMessage());
        } finally {
            unset($validated);
        }
    }
}
