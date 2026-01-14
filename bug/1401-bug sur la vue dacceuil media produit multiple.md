Le bug 1401 concerne l’affichage des médias sur la vue d’accueil lorsque celle-ci présente plusieurs produits. Dans ce contexte, les médias associés aux produits (images / vidéos) ne s’affichent pas correctement.

Comportement observé

une catégorie bug sur la vue
 
Les médias de certains produits ne s’affichent pas ou s’affichent de manière incorrecte.

Des médias peuvent être dupliqués, manquants, ou associés au mauvais produit.

Le problème apparaît principalement lorsque plusieurs produits sont chargés simultanément sur la vue d’accueil.

Comportement attendu

Chaque produit doit afficher uniquement ses propres médias.

Les médias doivent être chargés de façon cohérente et stable, quel que soit le nombre de produits affichés sur la vue d’accueil.

Impact

Dégradation de l’expérience utilisateur.

Confusion possible pour l’utilisateur final (mauvaise représentation des produits).

Risque commercial si les produits sont mal présentés.

Contexte / conditions d’apparition

Vue d’accueil avec produits multiples

Section média produit

Reproductible lors du chargement initial ou après rafraîchissement de la page

