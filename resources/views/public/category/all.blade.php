@extends('layout')

@section('title', 'All categories | Shopzone')

@section('content')
<div class="container py-5">

    {{-- Titre --}}
    <div class="text-center mb-5">
        <h1 class="fw-bold">Nos catégories</h1>
        <p class="text-muted">Découvrez nos univers de produits</p>
    </div>

    {{-- Catégories --}}
    <div class="row g-4">
        @forelse($categories as $category)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm border-0 category-card">

                    {{-- Image --}}
                    <div class="position-relative">
                        <img 
                            src="{{ $category->image 
                                    ? asset('storage/'.$category->image) 
                                    : asset('/pictures/shopzone.png') }}"
                            class="card-img-top"
                            alt="{{ $category->name }}"
                            style="height: 180px; object-fit: cover;"
                        >

                        {{-- Badge produits --}}
                        @php
                            $count = $category->productCount();

                            $badgeClass = match (true) {
                                $count <= 20  => 'bg-danger',
                                $count <= 50 => 'bg-primary',
                                default      => 'bg-success',
                            };
                        @endphp

                        <span class="badge {{ $badgeClass }} position-absolute top-0 end-0 m-2">
                            {{ $count }} produits
                        </span>
                    </div>

                    {{-- Contenu --}}
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">
                            {{ $category->name }}
                        </h5>

                        @if($category->description)
                            <p class="card-text text-muted small">
                                {{ Str::limit($category->description, 70) }}
                            </p>
                        @endif
                    </div>

                    {{-- Action --}}
                    <div class="card-footer bg-transparent border-0 text-center pb-3">
                        <a href="#"
                           class="btn btn-outline-primary btn-sm px-4">
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

</div>
@endsection
