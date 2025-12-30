<ul class="list-unstyled small text-muted d-flex flex-wrap gap-3">
    <li>✔ Paiement sécurisé</li>
    <li>✔ Livraison rapide</li>
    <li>✔ Retour sous 14 jours</li>
    <li>
        ✔ Produit encore disponible :
        <span class="{{ $product->is_active ? 'text-success' : 'text-danger' }}">
            {{ $product->is_active ? 'Oui' : 'Non' }}
        </span>
    </li>
</ul>
