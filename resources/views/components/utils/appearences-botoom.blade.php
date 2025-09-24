<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout 
        :heading="__('Appearance')" 
        :subheading="__('Update your account\'s appearance settings')"
    >
        <flux:radio.group x-data x-model="$flux.appearance" variant="segmented">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>
