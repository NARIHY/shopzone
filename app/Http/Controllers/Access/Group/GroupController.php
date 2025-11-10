<?php

namespace App\Http\Controllers\Access\Group;

use App\Common\GroupAdminView;
use App\Events\Utils\NotificationSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Access\Group\StoreGroupRequest;
use App\Http\Requests\Access\Group\UpdateGroupRequest;
use App\Jobs\Access\Group\ProcessCreateGroupJob;
use App\Jobs\Access\Group\ProcessUpdateGroupJob;
use App\Models\Access\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;

class GroupController extends Controller
{
    /**
     * Liste des groupes.
     */
    public function index()
    {
        return view(GroupAdminView::getListView());
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        return view(GroupAdminView::getCreateOrEditView());
    }

    /**
     * Enregistrer un groupe.
     */
    public function store(StoreGroupRequest $request)
    {
        try {
            ProcessCreateGroupJob::dispatch($request->validated());
            
            return redirect()->route('admin.groups.index');
        } catch(\Throwable $e)
        {
            event(new NotificationSent('warning', 'There was an error during the request. Reason: '.$e->getMessage()));
            return redirect()->back();
        } finally{
            unset($request);
        }
    }

    /**
     * Voir un groupe + ses utilisateurs.
     */
    public function show(Group $group)
    {
        //DO NOTHING
    }

    /**
     * Formulaire d’édition.
     */
    public function edit(Group $group)
    {
        return view(GroupAdminView::getCreateOrEditView(), [
            'group' => $group,
            'rolesInput' => \App\Models\Access\Role::all(),
        ]);
    }

    /**
     * Mettre à jour un groupe.
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        try {
            ProcessUpdateGroupJob::dispatch($group, $request->validated());
            
            return redirect()->back()
                ->with('success', 'Queued. Waiting for confirmation to finalize group update registration.');
        } catch(\Throwable $e)
        {
            event(new NotificationSent('warning', 'There was an error during the request. Reason: '.$e->getMessage()));
            return redirect()->back();
        } finally{
            unset($request);
        }
        
    }

    /**
     * Supprimer un groupe.
     */
    public function destroy(Group $group)
    {
        try {
            $group->delete();
            event(new NotificationSent('success','Group successfully deleted.' ));
            return redirect()->route('admin.groups.index');
        } catch (\Exception $e) {
            event(new NotificationSent('warning', 'There was an error during the request. Reason: '.$e->getMessage()));
            return redirect()->back();
        }
    }
}
