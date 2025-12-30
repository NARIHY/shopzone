@extends('layout')

@section('title', 'Bienvenue sur Shopzone - Votre Boutique en Ligne')

@section('content')

<div class="container py-5">

    {{-- HERO --}}
    <div class="text-center mb-5">
        <h1 class="fw-bold">
            Bienvenue sur <span class="text-primary">Shopzone</span>
        </h1>
        <p class="text-muted">Découvrez nos produits classés par catégorie</p>
    </div>

    {{-- CATEGORIES --}}
    @foreach($categories as $category)
        @if($category->products->count())

        <div class="mb-5">

            {{-- HEADER CATEGORIE --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-semibold">{{ $category->name }}</h3>
                <a href="#" class="btn btn-outline-primary btn-sm">
                    Voir plus
                </a>
            </div>

            {{-- CARROUSEL PRODUITS --}}
            <div id="carouselCategory{{ $category->id }}" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">

                    @foreach($category->products->chunk(4) as $chunkIndex => $productsChunk)
                        <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                            <div class="row g-4">

                                @foreach($productsChunk as $product)
                                <div class="col-12 col-sm-6 col-md-3">

                                    {{-- CARD PRODUIT --}}
                                    <div class="card h-100 shadow-sm border-0">

                                        {{-- MEDIA --}}
                                        @if($product->media->count() > 1)
                                        <div id="carouselProduct{{ $product->id }}"
                                             class="carousel slide"
                                             data-bs-interval="4000">

                                            <div class="carousel-inner">

                                                @foreach($product->media as $index => $media)
                                                    @if(str_starts_with($media->mime_type, 'image'))
                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                        <div class="product-media">
                                                            @if($product->discount_price)
                                                                <span class="badge bg-danger badge-discount">
                                                                    -{{ round(($product->discount_price / $product->price) * 100) }}%
                                                                </span>
                                                            @endif

                                                            <img src="{{ $media->url() }}"
                                                                 class="d-block w-100"
                                                                 style="height:180px; object-fit:cover;">
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endforeach

                                            </div>
                                        </div>

                                        @elseif($product->media->first())
                                        <div class="product-media">
                                            @if($product->discount_price)
                                                <span class="badge bg-danger badge-discount">
                                                    -{{ round(($product->discount_price / $product->price) * 100) }}%
                                                </span>
                                            @endif

                                            <img src="{{ $product->media->first()->url() }}"
                                                 class="card-img-top"
                                                 style="height:180px; object-fit:cover;">
                                        </div>

                                        @else
                                        <div class="product-media">
                                            <img src="/pictures/shopzone.png"
                                                 class="card-img-top"
                                                 style="height:180px; object-fit:cover;">
                                        </div>
                                        @endif

                                        {{-- BODY --}}
                                        <div class="card-body d-flex flex-column">

                                            <h6 class="fw-bold mb-1">
                                                {{ Str::limit($product->name, 40) }}
                                            </h6>

                                            <small class="text-muted mb-2">
                                                {{ $product->category?->name ?? '—' }}
                                            </small>

                                            {{-- PRIX --}}
                                            <div class="mb-2">
                                                @if($product->discount_price && $product->discount_price > 0)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="fw-bold text-danger fs-5">
                                                            {{ number_format($product->finalPrice(), 0, ',', ' ') }} Ar
                                                        </span>

                                                        <span class="text-muted text-decoration-line-through old-price">
                                                            {{ number_format($product->price, 0, ',', ' ') }} Ar
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="fw-bold text-primary fs-5">
                                                        {{ number_format($product->price, 0, ',', ' ') }} Ar
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- STOCK --}}
                                            <span class="badge bg-success mb-2">
                                                {{ $product->stock }} unités
                                            </span>

                                            {{-- DESCRIPTION --}}
                                            @if($product->description)
                                                <p class="text-muted small mb-3">
                                                    {{ Str::limit($product->description, 70) }}
                                                </p>
                                            @endif

                                            {{-- CTA --}}
                                            <a href="{{ route('public.product.show', $product) }}"
                                               class="btn btn-primary btn-sm mt-auto w-100">
                                                Voir le produit
                                            </a>

                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- CONTROLS --}}
                <button class="carousel-control-prev" type="button"
                        data-bs-target="#carouselCategory{{ $category->id }}"
                        data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle"></span>
                </button>

                <button class="carousel-control-next" type="button"
                        data-bs-target="#carouselCategory{{ $category->id }}"
                        data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle"></span>
                </button>
            </div>

        </div>
        @endif
    @endforeach

</div>

@endsection
