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

                {{-- CATEGORY HEADER --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-semibold">{{ $category->name }}</h3>
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        Voir plus
                    </a>
                </div>

                {{-- CARROUSEL DE PRODUITS --}}
                <div id="carouselCategory{{ $category->id }}" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">

                        @foreach($category->products->chunk(4) as $chunkIndex => $productsChunk)
                            <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                <div class="row g-4">

                                    @foreach($productsChunk as $product)
                                        <div class="col-12 col-sm-6 col-md-3">

                                            {{-- CARD PRODUIT --}}
                                            <div class="card h-100 shadow-sm border-0">

                                                {{-- CARROUSEL IMAGES PRODUIT --}}
                                                @if($product->media->count() > 1)
                                                    <div id="carouselProduct{{ $product->id }}"
                                                         class="carousel slide"
                                                         data-bs-ride="carousel"
                                                         data-bs-interval="4000">

                                                        <div class="carousel-inner">
                                                            @foreach($product->media as $index => $media)
                                                                @if(str_starts_with($media->mime_type, 'image'))
                                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                        <img src="{{ $media->url() }}"
                                                                             class="d-block w-100"
                                                                             style="height:180px; object-fit:cover;">
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>

                                                        {{-- Controls --}}
                                                        <button class="carousel-control-prev" type="button"
                                                                data-bs-target="#carouselProduct{{ $product->id }}"
                                                                data-bs-slide="prev">
                                                            <span class="carousel-control-prev-icon"></span>
                                                        </button>

                                                        <button class="carousel-control-next" type="button"
                                                                data-bs-target="#carouselProduct{{ $product->id }}"
                                                                data-bs-slide="next">
                                                            <span class="carousel-control-next-icon"></span>
                                                        </button>
                                                    </div>

                                                @elseif($product->media->first())
                                                    <img src="{{ $product->media->first()->url() }}"
                                                         class="card-img-top"
                                                         style="height:180px; object-fit:cover;">
                                                @else
                                                    <img src="/pictures/shopzone.png"
                                                         class="card-img-top"
                                                         style="height:180px; object-fit:cover;">
                                                @endif

                                                {{-- CONTENU PRODUIT --}}
                                                <div class="card-body d-flex flex-column">

                                                    <h6 class="fw-bold mb-1">
                                                        {{ Str::limit($product->name, 40) }}
                                                    </h6>

                                                    <small class="text-muted mb-2">
                                                        {{ $product->category?->name ?? '—' }}
                                                    </small>

                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="fw-bold text-primary">
                                                            {{ number_format($product->finalPrice(), 0, ',', ' ') }} Ar
                                                        </span>

                                                        <span class="badge bg-success">
                                                            {{ $product->stock }} unités
                                                        </span>
                                                    </div>

                                                    @if($product->description)
                                                        <p class="text-muted small mb-3">
                                                            {{ Str::limit($product->description, 70) }}
                                                        </p>
                                                    @endif

                                                    <a href="#"
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

                    {{-- CONTROLS PRODUITS --}}
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
