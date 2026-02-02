@extends('layout')

@section('title', $category->name . ' | Shopzone')

@section('content')
<div class="container py-5">

    {{-- Titre --}}
    <div class="text-center mb-5">
        <h1 class="fw-bold mb-2">
            Catégories
            @isset($category)
                | {{ $category->name }}
            @else
                | Toutes les catégories
            @endisset
        </h1>
        <p class="text-muted">Découvrez nos univers de produits</p>
    </div>

    {{-- Catégories --}}
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm category-card">

                    {{-- Image --}}
                    <div class="position-relative overflow-hidden">
                        <img
                            src="{{ $product->firstMediaPath()
                                ? asset('storage/'.$product->firstMediaPath())
                                : asset('/pictures/shopzone.png') }}"
                            class="card-img-top"
                            alt="{{ $product->name }}"
                            style="height: 180px; object-fit: cover;"
                        >

                        {{-- Badge produits --}}
                        @php
                            $count = $product->stock;
                            $badgeClass = match (true) {
                                $count <= 20  => 'bg-danger',
                                $count <= 50 => 'bg-primary',
                                default      => 'bg-success',
                            };
                        @endphp

                        <span class="badge {{ $badgeClass }} position-absolute top-0 end-0 m-2 px-3 py-2">
                            {{ $count }} produits
                        </span>
                    </div>

                    {{-- Contenu --}}
                    <div class="card-body text-center">
                        <h5 class="fw-semibold mb-2">{{ $product->name }}</h5>

                        @if($product->description)
                            <p class="text-muted small mb-0">
                                {{ Str::limit($product->description, 70) }}
                            </p>
                        @endif
                    </div>

                    {{-- Action --}}
                    <div class="card-footer bg-transparent border-0 text-center pb-4">
                        <a href="#"
                           class="btn btn-outline-primary btn-sm w-75">
                            Voir les produits
                        </a>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Aucune catégorie disponible pour le moment.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if(method_exists($products, 'links'))
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>
@endsection
