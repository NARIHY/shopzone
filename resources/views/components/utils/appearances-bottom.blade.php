<section class="w-full fixed bottom-0 right-0 flex justify-end">

    <flux:radio.group x-data x-model="$flux.appearance" variant="segmented">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>
</section>
