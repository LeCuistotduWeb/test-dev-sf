test-dev
========

Un stagiaire à créer le code contenu dans le fichier src/Controller/Home.php

Celui permet de récupérer des urls via un flux RSS ou un appel à l’API NewsApi. 
Celles ci sont filtrées (si contient une image) et dé doublonnées. 
Enfin, il faut récupérer une image sur chacune de ces pages.

Le lead dev n'est pas très satisfait du résultat, il va falloir améliorer le code.

Pratique : 
1. Revoir complètement la conception du code (découper le code afin de pouvoir ajouter de nouveaux flux simplement) 

Questions théoriques : 
1. Que mettriez-vous en place afin d'améliorer les temps de réponses du script
    - Mettre en cache la réponse de l'api ou faire tourner un cron pour récuperer les nouvelles images (evite de faire l'appel à chaque utilisateur)
3. Comment aborderiez-vous le fait de rendre scalable le script (plusieurs milliers de sources et images)
   - mettre en place une pagination
   - stocker en db les urls et urls d'images
   - Créer des batchs
   