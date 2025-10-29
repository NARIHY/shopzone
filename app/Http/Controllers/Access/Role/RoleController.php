<?php

namespace App\Http\Controllers\Access\Role;

use App\Common\RoleAdminView;
use App\Http\Controllers\Controller;
use App\Models\Access\Role;
use App\Http\Requests\Access\Group\StoreRoleRequest;
use App\Http\Requests\Access\Group\UpdateRoleRequest;
use App\Jobs\Access\Role\ProcessCreateRoleJob;
use App\Jobs\Access\Role\ProcessUpdateRoleJob;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view(RoleAdminView::getListView());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(RoleAdminView::getCreateOrEditView());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $storeRoleRequest)
    {
        try {
        ProcessCreateRoleJob::dispatch($storeRoleRequest->validated());

        return redirect()->route('admin.roles.index')->with('success', __('Queued. Waiting for confirmation to finalize role create registration.'));
        } catch(\Throwable $e)
        {
            return redirect()->back()->with('warning', 'There was an error during the request. Reason: '.$e->getMessage());
        } finally{
            unset($storeRoleRequest);
        } 
       
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view(RoleAdminView::getCreateOrEditView(), [
            'role' => Role::findOrFail($role->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $updateRoleRequest, Role $role)
    {
        try {
            ProcessUpdateRoleJob::dispatch($role, $updateRoleRequest->validated());
        return redirect()->back()->with('success', __('Queued. Waiting for confirmation to finalize role update registration.'));
        } catch(\Throwable $e)
        {
            return redirect()->back()->with('warning', 'There was an error during the request. Reason: '.$e->getMessage());
        } finally{
            unset($updateRoleRequest, $role);
        } 
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
