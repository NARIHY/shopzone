<?php

namespace App\Http\Controllers\Access;

use App\Common\GroupAdminView;
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
        return view(GroupAdminView::getListView(),);
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
        ProcessCreateGroupJob::dispatch($request->validated());
        unset($request);
        return redirect()->route('admin.groups.index')
            ->with('success', 'Queued. Waiting for confirmation to finalize group create registration.');
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
        ProcessUpdateGroupJob::dispatch($group, $request->validated());
        unset($group, $request);
        return redirect()->back()
            ->with('success', 'Queued. Waiting for confirmation to finalize group update registration.');
    }

    /**
     * Supprimer un groupe.
     */
    public function destroy(Group $group)
    {
        try {
            $group->delete();

            return redirect()->route('admin.groups.index')
                ->with('success', 'Groupe supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
