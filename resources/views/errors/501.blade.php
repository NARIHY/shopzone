@extends('layout')

@section('title', 'APPLICATION EN PANNE')

@section('content')

<div class="page-title light-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">501</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{route('public.home')}}">Home</a></li>
        <li class="current">501</li>
      </ol>
    </nav>
  </div>
</div>

<section id="error-404" class="error-404 section">
  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="text-center">
      <div class="error-icon mb-4" data-aos="zoom-in" data-aos-delay="200">
        <i class="bi bi-lock"></i>
      </div>

      <h1 class="error-code mb-4" data-aos="fade-up" data-aos-delay="300">501</h1>

      <h2 class="error-title mb-3" data-aos="fade-up" data-aos-delay="400">
        Oops! Accès non autorisé
      </h2>

      <p class="error-text mb-4" data-aos="fade-up" data-aos-delay="500">
        Vous devez être connecté ou disposer des permissions nécessaires pour accéder à cette page.
      </p>

      <div class="error-action" data-aos="fade-up" data-aos-delay="600">
        <a href="{{route('public.home')}}" class="btn btn-primary">Retour à l’accueil</a>
      </div>
    </div>
  </div>
</section>
@endsection