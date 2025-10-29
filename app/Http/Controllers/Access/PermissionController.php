<?php

namespace App\Http\Controllers\Access;

use App\Common\PermissionView;
use App\Http\Controllers\Controller;
use App\Http\Requests\Access\Permission\PermissionStoreRequest;
use App\Http\Requests\Access\Permission\PermissionUpdateRequest;
use App\Models\Access\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     */
    public function index(): View
    {
        return view(PermissionView::getPermissionListView());
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create(): View
    {
        return view(PermissionView::getPermissionCreateOrEditView());
    }

    /**
     * Store a newly created permission in storage.
     */
    public function store(PermissionStoreRequest $request): RedirectResponse
    {
        try {
            Permission::create($request->validated());
            return redirect()
                ->route('admin.permissions.index')
                ->with('success', 'Permission created successfully.');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', 'Error saving permission: ' . $e->getMessage());
        } finally {
            unset($request);
        }
    }

    /**
     * Display the specified permission.
     */
    public function show(Permission $permission)
    {
        //Nothing to do here
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission): View
    {
        return view(PermissionView::getPermissionCreateOrEditView(), compact('permission'));
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(PermissionUpdateRequest $request, Permission $permission): RedirectResponse
    {
        try {
            $permission->update($request->validated());
            return redirect()
                ->back()
                ->with('success', 'Permission updated successfully.');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', 'Error updating permission: ' . $e->getMessage());
        } finally {
            unset($request, $permission);
        }
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        try {
            $permission->delete();
            return redirect()
                ->back()
                ->with('success', 'Permission deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', 'Error deleting permission: ' . $e->getMessage());
        } finally {
            unset($permission);
        }
    }
}
