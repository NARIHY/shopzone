<?php

namespace App\Http\Controllers\Tenant;

use App\Common\TenantInformationView;
use App\Events\Utils\NotificationSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\TenantInformationToCreateRequest;
use App\Models\Tenant\TenantInformations;
use Illuminate\Support\Str;

class TenantInformationController extends Controller
{
    /**
     * Afficher la seule information tenant
     */
    public function index()
    {
        return view(TenantInformationView::getListView(), [
            'tenant' => TenantInformations::query()->first()
        ]);
    }

    /**
     * Create ou Update (single row system)
     */
    public function store(TenantInformationToCreateRequest $request)
    {
        try {
            $tenant = TenantInformations::query()->first();
            $data = $request->validated();

            if ($request->hasFile('logo_path')) {

                $dateFolder = now()->format('d-m-Y');
                $file = $request->file('logo_path');

                $filename = Str::slug($data['tenant_name'])
                    . '.' . $file->getClientOriginalExtension();

                $path = $file->storeAs(
                    "tenant/{$dateFolder}",
                    $filename,
                    'public'
                );

                $data['logo_path'] = $path;
            }

            if (!$tenant) {
                TenantInformations::create($data);
                event(new NotificationSent('success', 'Tenant information created successfully'));
            } else {
                $tenant->update($data);
                event(new NotificationSent('success', 'Tenant information updated successfully'));
            }

            return redirect()->back();

        } catch (\Exception $e) {
            event(new NotificationSent(
                'error',
                'An error occurred while saving tenant information: ' . $e->getMessage()
            ));
            return redirect()->back()->withInput();
        }
    }

    /**
     * PAS UTILISÉ
     */
    public function create(){}
    public function edit(TenantInformations $tenantInformations){}
    public function show(TenantInformations $tenantInformations){}
    public function update(){}
    public function destroy(TenantInformations $tenantInformations){}
}
