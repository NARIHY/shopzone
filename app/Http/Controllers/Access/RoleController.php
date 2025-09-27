<?php

namespace App\Http\Controllers\Access;

use App\Common\RoleAdminView;
use App\Http\Controllers\Controller;
use App\Models\Access\Role;
use App\Http\Requests\Access\StoreRoleRequest;
use App\Http\Requests\Access\UpdateRoleRequest;

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
    public function store(StoreRoleRequest $request)
    {
        try {
            $data = [
                'name' => $request->validated('name'),
                'description' => $request->validated('description', null),
                'is_active' => $request->validated('is_active') ?? false,
            ];

            Role::create($data);
            return redirect()->route('admin.roles.index')->with('success', __('Role created successfully.'));

        } catch( \Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
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
    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            $data = [
                'name' => $request->validated('name'),
                'description' => $request->validated('description', null),
                'is_active' => $request->validated('is_active') ?? false,
            ];

            $role->update($data);
            return redirect()->route('admin.roles.index')->with('success', __('Role updated successfully.'));
        } catch( \Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
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
