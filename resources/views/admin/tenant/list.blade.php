<x-layouts.app :title="__('Tenant Information')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        
        <div class="flex flex-col gap-6 rounded-xl p-6 bg-white dark:bg-gray-800 shadow-md">
            <h1 class="text-center text-lg font-bold uppercase text-black dark:text-white">
                {{ __('Tenant Information') }}
            </h1>
            <form
                action="{{ isset($tenant) 
                    ? route('admin.tenantinformations.update', $tenant) 
                    : route('admin.tenantinformations.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="flex flex-col gap-4">

                @csrf
                @isset($tenant)
                    @method('PUT')
                @endisset

                {{-- Tenant Name --}}
                <x-form.input
                    name="tenant_name"
                    label="{{ __('Tenant Name') }}"
                    :value="old('tenant_name', $tenant->tenant_name ?? '')"
                    help="{{ __('Enter the tenant name.') }}"
                />

                {{-- Address --}}
                <x-form.textarea
                    name="address"
                    label="{{ __('Address') }}"
                    :value="old('address', $tenant->address ?? '')"
                    rows="3"
                />

                {{-- Phone --}}
                <x-form.input
                    name="phone"
                    label="{{ __('Phone Number') }}"
                    :value="old('phone', $tenant->phone ?? '')"
                />

                {{-- Email --}}
                <x-form.input
                    type="email"
                    name="email"
                    label="{{ __('Email Address') }}"
                    :value="old('email', $tenant->email ?? '')"
                />




                {{-- RIB --}}
                <x-form.input
                    name="RIB"
                    label="{{ __('RIB') }}"
                    :value="old('RIB', $tenant->RIB ?? '')"
                />

                {{-- SIRET --}}
                <x-form.input
                    name="SIRET"
                    label="{{ __('SIRET') }}"
                    :value="old('SIRET', $tenant->SIRET ?? '')"
                />

                {{-- VAT Number --}}
                <x-form.input
                    name="VAT_number"
                    label="{{ __('VAT Number') }}"
                    :value="old('VAT_number', $tenant->VAT_number ?? '')"
                />

                {{-- Logo --}}
                {{-- File Upload --}}
                @if($tenant && $tenant->logo_path)
                <div class="row mb-3">
                    <div class="col md-6">
                        <x-form.file 
                            name="logo_path"
                            :value="$tenant->logo_path ?? null"
                            help="{{ __('Company Logo') }}"
                        />
                    </div>
                    <div class="col md-6">
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $tenant->logo_path) }}" alt="{{ __('Current Logo') }}" class="h-20 w-auto object-contain">
                            </div>
                    </div>
                </div>

                @else
                <x-form.file 
                            name="logo_path"
                            :value="$tenant->logo_path ?? null"
                            help="{{ __('Company Logo') }}"
                        />
                @endif

                

                {{-- Submit --}}
                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded
                               transition-colors duration-200
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800">
                        {{ isset($tenant) ? __('Update Tenant') : __('Create Tenant') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-layouts.app>
