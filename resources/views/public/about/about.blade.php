@extends('layout')

@section('title', 'A propos de fusion - Votre Boutique en Ligne')

@section('customcss')
<style>
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.7;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .timeline-item {
            border-left: 3px solid #667eea;
            padding-left: 20px;
            margin-bottom: 30px;
            position: relative;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 0;
            width: 13px;
            height: 13px;
            background: #667eea;
            border-radius: 50%;
        }
        .stats-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
        }
        .faq-item {
            border: none;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
        }
        .faq-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .faq-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        .faq-button:focus {
            box-shadow: none;
        }
        .contact-card {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            border-radius: 15px;
            padding: 30px;
        }
        .section-title {
            position: relative;
            margin-bottom: 50px;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endsection


@section('content')
    

    <!-- Notre Histoire -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title display-6 fw-bold">Notre Histoire</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="timeline-item">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title text-primary"><i class="fas fa-lightbulb me-2"></i>2008 - L'idée</h5>
                                <p class="card-text">Née d'une idée de deux sœurs, avec le mari de la cadette, ont remarqué qu'il n'y avait pas assez de magasins spécialistes en cadeau à Tana. Surtout un cadeau qui demandait plus d'originalité.</p>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title text-primary"><i class="fas fa-store me-2"></i>Les débuts</h5>
                                <p class="card-text">Ils ont commencé par louer un mur dans un centre commercial. Le stockage était encore dans une toute petite chambre. Plus les mois passaient, plus les placards se remplissaient des stocks de produits, envahissant même la cuisine !</p>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title text-primary"><i class="fas fa-building me-2"></i>Première boutique</h5>
                                <p class="card-text">Quelques mois plus tard, ouverture de la première vraie boutique à Ankadifotsy, qui reste le principal magasin aujourd'hui. Accueil du premier employé et en moins de deux ans, l'équipe a triplé !</p>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title text-primary"><i class="fas fa-cogs me-2"></i>Personnalisation</h5>
                                <p class="card-text">Acquisition de la première machine de personnalisation par sublimation : mugs, coussins... Puis après 10 ans, expansion vers les entreprises avec l'impression UV et grand format.</p>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title text-primary"><i class="fas fa-users me-2"></i>Aujourd'hui</h5>
                                <p class="card-text">Une équipe de 33 personnes qui s'emploie quotidiennement à proposer des idées de cadeaux innovantes. Depuis le Covid, engagement de couturiers pour la conception de vêtements professionnels.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Nos Services -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title display-6 fw-bold">Pourquoi choisir Fusion Gift ?</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <div class="card-body p-4">
                            <div class="feature-icon">
                                <i class="fas fa-paint-brush fa-lg"></i>
                            </div>
                            <h5 class="card-title">Personnalisation sur mesure</h5>
                            <p class="card-text">Nous personnalisons tous vos cadeaux dans notre atelier avec un contrôle qualité rigoureux.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <div class="card-body p-4">
                            <div class="feature-icon">
                                <i class="fas fa-shipping-fast fa-lg"></i>
                            </div>
                            <h5 class="card-title">Livraison rapide</h5>
                            <p class="card-text">Emballage soigné à la main avec carte personnalisée, prêt à offrir.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <div class="card-body p-4">
                            <div class="feature-icon">
                                <i class="fas fa-headset fa-lg"></i>
                            </div>
                            <h5 class="card-title">Service client dédié</h5>
                            <p class="card-text">Vanessa vous accompagne dans votre processus d'achat avec expertise et bienveillance.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title display-6 fw-bold">Questions fréquentes</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="accordion" id="faqAccordion">
                        <div class="faq-item">
                            <button class="faq-button w-100 text-start d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                <span>Quelles sont les meilleures idées de cadeaux personnalisés ?</span>
                                <i class="fas fa-plus"></i>
                            </button>
                            <div id="faq1" class="collapse" data-bs-parent="#faqAccordion">
                                <div class="p-4 bg-white">
                                    <p class="mb-3"><strong>Notre top 5 des cadeaux personnalisés :</strong></p>
                                    <ol class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-coffee text-primary me-2"></i><strong>Mug personnalisé</strong> - Pour bien débuter la journée à petit prix</li>
                                        <li class="mb-2"><i class="fas fa-shopping-bag text-primary me-2"></i><strong>Tote bag</strong> - Très utile et réutilisable, à porter partout</li>
                                        <li class="mb-2"><i class="fas fa-trophy text-primary me-2"></i><strong>Trophée</strong> - Pas seulement pour des événements officiels</li>
                                        <li class="mb-2"><i class="fas fa-key text-primary me-2"></i><strong>Porte-clés personnalisé</strong> - Le cadeau indémodable</li>
                                        <li class="mb-2"><i class="fas fa-tshirt text-primary me-2"></i><strong>T-shirt</strong> - Un grand classique qui fait toujours plaisir</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-button w-100 text-start d-flex justify-content-between align-items-center collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                <span>Quels cadeaux peuvent être personnalisés ?</span>
                                <i class="fas fa-plus"></i>
                            </button>
                            <div id="faq2" class="collapse" data-bs-parent="#faqAccordion">
                                <div class="p-4 bg-white">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary"><i class="fas fa-utensils me-2"></i>Cuisine</h6>
                                            <ul class="list-unstyled small mb-3">
                                                <li>• Verres personnalisés</li>
                                                <li>• Tabliers personnalisés</li>
                                                <li>• Planches à découper</li>
                                                <li>• Sets de cuillères</li>
                                                <li>• Sous-verres</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary"><i class="fas fa-home me-2"></i>Décoration</h6>
                                            <ul class="list-unstyled small mb-3">
                                                <li>• Posters personnalisés</li>
                                                <li>• Coussins personnalisés</li>
                                                <li>• Couvertures</li>
                                                <li>• Paillassons</li>
                                                <li>• Posters en bois gravé</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted"><em>Et bien plus encore ! L'embarras du choix vous attend.</em></p>
                                </div>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-button w-100 text-start d-flex justify-content-between align-items-center collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                <span>Comment personnalisez-vous nos cadeaux ?</span>
                                <i class="fas fa-plus"></i>
                            </button>
                            <div id="faq3" class="collapse" data-bs-parent="#faqAccordion">
                                <div class="p-4 bg-white">
                                    <p>Nous préparons toutes les commandes dans nos locaux et les cadeaux sont gravés ou imprimés directement dans notre atelier de personnalisation pour assurer une haute qualité.</p>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Possibilités :</strong> Dates, textes, photos, prénoms, surnoms... Les combinaisons sont multiples !
                                    </div>
                                    <p class="mb-0">Il est impossible que votre ami trouve un double similaire du cadeau reçu !</p>
                                </div>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-button w-100 text-start d-flex justify-content-between align-items-center collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                <span>Quels sont vos modes de paiement ?</span>
                                <i class="fas fa-plus"></i>
                            </button>
                            <div id="faq4" class="collapse" data-bs-parent="#faqAccordion">
                                <div class="p-4 bg-white">
                                    <p class="mb-3">Payez en toute sécurité sur notre site <strong>www.fusiongift.mg</strong></p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-credit-card text-primary me-3"></i>
                                                <span>Carte bancaire</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-mobile-alt text-primary me-3"></i>
                                                <span>Mobile money</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-money-check text-primary me-3"></i>
                                                <span>Chèque & Virement</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-coins text-primary me-3"></i>
                                                <span>Espèces</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title display-6 fw-bold">Contactez-nous</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-card">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                <div class="feature-icon mx-auto mb-3">
                                    <i class="fas fa-user-tie fa-lg"></i>
                                </div>
                                <h5>Vanessa</h5>
                                <p class="text-muted mb-0">Service client dédié</p>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-phone text-primary me-3"></i>
                                            <div>
                                                <small class="text-muted">Téléphone</small><br>
                                                <strong>034 97 464 01</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-phone text-primary me-3"></i>
                                            <div>
                                                <small class="text-muted">Alternative</small><br>
                                                <strong>034 04 381 39</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-envelope text-primary me-3"></i>
                                            <div>
                                                <small class="text-muted">Email</small><br>
                                                <strong>commercial1@fusiongift.mg</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customjs')
<script>
        // Animation des icônes FAQ
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(button => {
            button.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (this.classList.contains('collapsed')) {
                    icon.classList.remove('fa-minus');
                    icon.classList.add('fa-plus');
                } else {
                    icon.classList.remove('fa-plus');
                    icon.classList.add('fa-minus');
                }
            });
        });

        // Animation au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observer les éléments de timeline
        document.querySelectorAll('.timeline-item').forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = `all 0.6s ease ${index * 0.1}s`;
            observer.observe(item);
        });

        // Observer les cartes de service
        document.querySelectorAll('.feature-icon').forEach((item, index) => {
            const card = item.closest('.card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = `all 0.6s ease ${index * 0.2}s`;
            observer.observe(card);
        });
    </script>
@endsection