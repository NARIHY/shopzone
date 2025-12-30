@extends('layout')

@section('title', $product->name . ' | Shopzone')

@section('content')
<div class="container py-5">

    <div class="row g-5">

        {{-- MEDIA (IMAGES + VIDEOS) --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    @if($product->media->count())
                        <div id="productCarousel" class="carousel slide" data-bs-ride="false">
                            <div class="carousel-inner rounded">

                                @foreach($product->media as $i => $media)
                                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">

                                        {{-- IMAGE --}}
                                        @if(str_starts_with($media->mime_type, 'image'))
                                            <img
                                                src="{{ $media->url() }}"
                                                alt="{{ $media->title ?? $product->name }}"
                                                class="d-block w-100 rounded-lg"
                                                style="height:420px; object-fit:contain;"
                                            >

                                        {{-- VIDEO --}}
                                        @elseif(str_starts_with($media->mime_type, 'video'))
                                            <video
                                                controls
                                                preload="metadata"
                                                class="d-block w-100 rounded-lg"
                                                style="height:420px; object-fit:contain;">
                                                <source src="{{ $media->url() }}" type="{{ $media->mime_type }}">
                                                {{ __('Your browser does not support the video tag.') }}
                                            </video>
                                        @endif

                                    </div>
                                @endforeach

                            </div>

                            @if($product->media->count() > 1)
                                <button class="carousel-control-prev" type="button"
                                        data-bs-target="#productCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>

                                <button class="carousel-control-next" type="button"
                                        data-bs-target="#productCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            @endif
                        </div>
                    @else
                        <img src="/pictures/shopzone.png"
                             class="img-fluid rounded"
                             alt="Shopzone">
                    @endif

                </div>
            </div>
        </div>

        {{-- INFOS PRODUIT --}}
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

            <hr class="my-4">

            {{-- INFOS TECHNIQUES --}}
            <livewire:product.product-features :product="$product" />

        </div>
    </div>
</div>
@endsection
