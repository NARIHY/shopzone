@extends('layout')

@section('title', $product->name . ' | Shopzone')

@section('content')
<div class="container py-5">

    <div class="row g-5">

        {{-- IMAGES --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    @if($product->media->count())
                        <div id="productCarousel" class="carousel slide">
                            <div class="carousel-inner rounded">
                                @foreach($product->media as $i => $media)
                                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                        <img src="{{ $media->url() }}"
                                             class="d-block w-100"
                                             style="height:420px; object-fit:contain;"
                                             alt="{{ $product->name }}">
                                    </div>
                                @endforeach
                            </div>

                            @if($product->media->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            @endif
                        </div>
                    @else
                        <img src="/pictures/shopzone.png" class="img-fluid rounded">
                    @endif

                </div>
            </div>
        </div>

        {{-- INFOS --}}
        <div class="col-lg-6">

            {{-- Catégorie --}}
            @if($product->category)
                <span class="badge bg-light text-dark mb-2">
                    {{ $product->category->name }}
                </span>
            @endif

            <h1 class="fw-bold mb-2">{{ $product->name }}</h1>

            {{-- SKU --}}
            <p class="text-muted small mb-3">
                Référence : <strong>{{ $product->sku }}</strong>
            </p>

            {{-- PRIX --}}
            <div class="mb-3">
                @if($product->discount_price > 0)
                    <span class="text-muted text-decoration-line-through fs-5">
                        {{ number_format($product->price, 2) }} MGA
                    </span>

                    <span class="fs-2 fw-bold text-danger ms-2">
                        {{ number_format($product->finalPrice(), 2) }} MGA
                    </span>

                    <span class="badge bg-danger ms-2">
                        -{{ number_format((($product->price - $product->finalPrice()) / $product->price) * 100, 0) }}%
                    </span>
                @else
                    <span class="fs-2 fw-bold text-primary">
                        {{ number_format($product->price, 2) }} MGA
                    </span>
                @endif
            </div>


            {{-- STOCK --}}
            @if($product->inStock())
                <span class="badge bg-success mb-3">
                    En stock ({{ $product->stock }})
                </span>
            @else
                <span class="badge bg-danger mb-3">
                    Rupture de stock
                </span>
            @endif

            {{-- DESCRIPTION --}}
            <p class="text-muted my-4">
                {{ $product->description }}
            </p>

            {{-- ACTIONS --}}
            <div class="d-flex gap-3">
                <button class="btn btn-primary btn-lg"
                        {{ !$product->inStock() ? 'disabled' : '' }}>
                    🛒 Ajouter au panier
                </button>

                <button class="btn btn-outline-secondary btn-lg">
                    🤍
                </button>
            </div>

            {{-- INFOS TECHNIQUES --}}
            <hr class="my-4">

            <livewire:product.product-features :product="$product" />

        </div>
    </div>
</div>
@endsection
