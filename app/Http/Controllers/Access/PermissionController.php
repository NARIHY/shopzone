<?php

namespace App\Http\Controllers\Access;

use App\Common\PermissionView;
use App\Http\Controllers\Controller;
use App\Models\Access\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        // Logic to list permissions
        return view(PermissionView::getPermissionListView());
    }

    public function show(Permission $permission)
    {
        // Logic to show a specific permission
    }

    public function create()
    {
        // Logic to show form for creating a permission
    }

    public function store(Request $request)
    {
        // Logic to store a new permission
    }

    public function edit(Permission $permission)
    {
        // Logic to show form for editing a permission
    }

    public function update(Request $request, Permission $permission)
    {
        // Logic to update a specific permission
    }

    public function destroy(Permission $permission)
    {
        // Logic to delete a specific permission
    }
}
