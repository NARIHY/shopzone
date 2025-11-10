<?php

namespace App\Http\Controllers\Access\Role;

use App\Common\RoleToPermissionView;
use App\Events\Utils\NotificationSent;
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
        $allPermissions = Cache::remember('permissions_all', 600, function () {
            return Permission::orderBy('name')->get(['id', 'name', 'description', 'is_active']);
        });

        // Permissions déjà assignées au rôle
        $assignedPermissions = Cache::remember("role_{$role->id}_permissions", 600, function () use ($role) {
            return $role->permissions()->pluck('permissions.id')->toArray();
        });

        // Préparer la vue
        $view = view(
            RoleToPermissionView::getListRoleToPermissionView(),
            compact('role', 'allPermissions', 'assignedPermissions')
        );

        // Libération mémoire
        unset($allPermissions, $assignedPermissions);

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

            event(new NotificationSent('success', 'Permissions updated for role: ' . $role->roleName));

            return redirect()
                ->route('admin.roleToPermission.index', $role->id);
        } catch (\Throwable $e) {
            event(new NotificationSent('warning', 'There was an error during the request. Reason: ' . $e->getMessage()));
            return redirect()->back();
        } finally {
            unset($validated);
        }
    }
}
