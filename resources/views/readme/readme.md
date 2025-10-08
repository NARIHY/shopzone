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


## Price input
<x-input.price 
    name="price"
    label="Prix du produit"
    value="{{ old('price', 15000) }}"
    help="Entrez le prix du produit en Ariary."
/>

## Input number 
<x-input.number 
    name="quantity"
    label="Quantité en stock"
    value="{{ old('quantity', 10) }}"
    min="0"
    max="1000"
    step="1"
    suffix="pcs"
    help="Indiquez le nombre d’articles disponibles."
/>
