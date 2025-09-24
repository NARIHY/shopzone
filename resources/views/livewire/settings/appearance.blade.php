<?php

use Livewire\Volt\Component;

new class extends Component {
    // Ici tu peux mettre des propriétés ou méthodes
    public string $appearance = 'system';

    public function updatedAppearance($value)
    {
        // Tu peux sauvegarder le choix (DB, user prefs, etc.)
        auth()->user()?->update(['appearance' => $value]);
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading="__('Update your account\'s appearance settings')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>
