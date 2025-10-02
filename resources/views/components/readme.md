## Type a head 

<x-select-typeahead 
    name="country"
    :options="[
        'mg' => 'Madagascar',
        'fr' => 'France',
        'us' => 'United States',
        'ca' => 'Canada',
        'de' => 'Germany',
    ]"
    placeholder="Choisissez un pays"
    selected="fr"
/>
