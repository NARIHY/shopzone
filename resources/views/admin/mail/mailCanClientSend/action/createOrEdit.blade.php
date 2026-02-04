<x-layouts.app :title="__('Edit or Create Mail Can Client Send')">

    <div class="pagetitle mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ __('Edit or Create Mail Can Client Send') }}
        </h1>
        <nav>
            <ol class="breadcrumb flex space-x-2 text-gray-600 dark:text-gray-400">
            <li class="breadcrumb-item">
                <a href="{{route('admin.mailcanclientsend.index')}}" class="hover:text-blue-600 dark:hover:text-blue-400">
                    {{__('Mail Can Client Send')}}
                </a>
            </li>
            <li class="breadcrumb-item">/</li>
            <li class="breadcrumb-item font-semibold text-gray-800 dark:text-gray-100">{{__('Edit or Create Mail Can Client Send')}}</li>
            </ol>
        </nav>
    </div>


    <div class="flex flex-col gap-6 rounded-xl p-6 bg-white dark:bg-gray-800 shadow-md">


        {{-- Form Title --}}
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
            {{ isset($mailCanClientSend) ? __('Edit Mail Can Client Send') : __('Create Mail Can Client Send') }}
        </h1>

        {{-- Form --}}
        <form action="{{ isset($mailCanClientSend) ? route('admin.mailcanclientsend.update', $mailCanClientSend->id) : route('admin.mailcanclientsend.store') }}"
              method="POST"
              class="flex flex-col gap-4">

            @csrf
            @if(isset($mailCanClientSend))
                @method('PUT')
            @endif

            {{-- mail title --}}
            <x-form.input 
                name="title" 
                label="{{ __('Mail Title') }}" 
                :value="$mailCanClientSend->title ?? ''"
                help="Provide a unique name for the mail."
            />

            {{-- Mail limit --}}
            <x-input.number 
                name="mail_limit"
                label="{{ __('Mail limits') }}"
                value="{{ old('stock', $mailCanClientSend->stock ?? 0) }}"
                min="100"
                max="3000"
                step="1"
                suffix="units"
                help="{{ __('Indicate the number of mail available.') }}"
            />

            {{-- Valid from --}}
            <x-form.datetime
                name="valid_from"
                label="{{__('Valid from')}}"
                :value="isset($mailCanClientSend) ? $mailCanClientSend->valid_from : now()"
                help="Provide a start date and time for the mail to be valid."
            />

            {{-- Valid to --}}
            <x-form.datetime
                name="valid_to"
                label="{{__('Valid to')}}"
                :value="isset($mailCanClientSend) ? $mailCanClientSend->valid_to : now()->addMonth()"
                help="Provide an end date and time for the mail to be valid."
            />


            {{-- Is Active Toggle --}}
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox"
                       name="is_active"
                       id="is_active"
                       value="1"
                       {{ old('is_active', $role->is_active ?? false) ? 'checked' : '' }}
                       class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                <label for="is_active" class="text-gray-700 dark:text-gray-200 font-medium">
                    {{ __('Active') }}
                </label>
            </div>


            {{-- Submit Button --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded
                               transition-colors duration-200
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    {{ isset($mailCanClientSend) ? __('Update Mail Can Client Send') : __('Create Mail Can Client Send') }}
                </button>
            </div>

        </form>
    </div>
</x-layouts.app>
