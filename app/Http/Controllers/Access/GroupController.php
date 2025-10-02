<?php

namespace App\Http\Controllers\Access;

use App\Common\GroupAdminView;
use App\Http\Controllers\Controller;
use App\Http\Requests\Access\StoreGroupRequest;
use App\Http\Requests\Access\UpdateGroupRequest;
use App\Models\Access\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Liste des groupes.
     */
    public function index()
    {
        $groups = Group::with('role')->paginate(10);

        return view(GroupAdminView::getListView(), compact('groups'));
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
            $group = Group::create($request->validated());

            return redirect()->route('groups.index')
                ->with('success', 'Groupe créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
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
        ]);
    }

    /**
     * Mettre à jour un groupe.
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        try {
            $group->update($request->validated());

            return redirect()->route('groups.index')
                ->with('success', 'Groupe mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Supprimer un groupe.
     */
    public function destroy(Group $group)
    {
        try {
            $group->delete();

            return redirect()->route('groups.index')
                ->with('success', 'Groupe supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }
}
