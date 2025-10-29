<?php

namespace App\Http\Controllers\Access\Group;

use App\Common\AffectGroupUserView;
use App\Http\Controllers\Controller;
use App\Http\Requests\Access\GroupUser\GroupUserStoreRequest;
use App\Http\Requests\Access\GroupUser\GroupUserUpdateRequest;
use App\Models\Access\GroupUser;
use Illuminate\Http\Request;

class GroupUserController extends Controller
{
    public function index()
    {
        return view(AffectGroupUserView::getListView());
    }

    public function create()
    {
        return view(AffectGroupUserView::getCreateOrEditView());
    }

    public function store(GroupUserStoreRequest $groupUserStoreRequest)
    {
        try {
             GroupUser::create($groupUserStoreRequest->validated());
            return redirect()->route('admin.groupUsers.index')->with('success', __('Group user created successfully.'));
        } catch(\Throwable $e)
        {
            return redirect()->back()->with('warning', 'There was an error during the request. Reason: '.$e->getMessage());
        } finally{
            unset($groupUserStoreRequest);
        }       
    }

    public function edit(GroupUser $groupUser)
    {        
        return view(AffectGroupUserView::getCreateOrEditView(), [
            'groupUser' => GroupUser::with('group', 'user')->findOrFail($groupUser->id),
        ]);
    }

    public function update(GroupUserUpdateRequest $groupUserUpdateRequest, GroupUser $groupUser)
    {
        try {
            $groupUser->update($groupUserUpdateRequest->validated());

            return redirect()->route('admin.groupUsers.index')->with('success', __('Group user updated successfully.'));
        } catch(\Throwable $e)
        {
            return redirect()->back()->with('warning', 'There was an error during the request. Reason: '.$e->getMessage());
        } finally{
            unset($groupUserUpdateRequest, $groupUser);
        }        
    }

    public function destroy(GroupUser $groupUser)
    {
        try {
           $groupUser->delete();

            return redirect()->route('admin.groupUsers.index')->with('success', __('Group user deleted successfully.'));
        } catch(\Throwable $e)
        {
            return redirect()->back()->with('warning', 'There was an error during the request. Reason: '.$e->getMessage());
        } finally{
            unset($groupUser);
        }            
    }

}
