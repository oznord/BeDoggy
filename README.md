# BeDoggy

> Application web de mise en relation autour des prestations pour chiens.

## À propos du projet

BeDoggy est une application web réalisée via le framework Symfony.

L'objectif est de permettre aux propriétaires de chiens de découvrir des prestations adaptées à leurs besoins et d'envoyer une demande personnalisée. Un espace administrateur permet ensuite de consulter les demandes et de modifier leur statut ou d'envoyer une réponse au client.

Le projet a été développé avec Symfony et Doctrine, en privilégiant une interface simple, claire et adaptée à une utilisation sur ordinateur comme sur mobile.

## Fonctionnalités

### Pour les visiteurs

- consulter la page d'accueil ;  
- découvrir les prestations proposées ;  
- consulter le détail d'une prestation ;  
- créer un compte ;  
- se connecter et se déconnecter.

### Pour les utilisateurs connectés

- créer une demande pour leur chien ;  
- sélectionner une ou plusieurs prestations ;  
- renseigner la description du besoin, la date souhaitée et les informations du chien ;  
- consulter l'historique de leurs demandes ;  
- consulter le statut et la réponse de l'administrateur.

### Pour l'administrateur

- consulter les demandes des clients ;  
- modifier le statut d'une demande ;  
- répondre au client ;  
- ajouter, modifier et supprimer des prestations.

## Aperçu de l'application

### Page d'accueil

![Page d'accueil](docs/AccueilBeDoggy.png)

![Page d'accueil](docs/Accueil2BeDoggy.png)

*Présentation de BeDoggy et aperçu des prestations disponibles.*

### Liste des prestations

![Liste des prestations](docs/Prestations.png)

*Les différentes prestations proposées aux propriétaires de chiens.*

### Création d'une demande

![Création d'une demande](docs/AjouterDemande.png)

*Formulaire permettant de décrire le besoin et de sélectionner plusieurs prestations.*

### Suivi des demandes

![Suivi des demandes](docs/MesDemandes.png)

*Historique des demandes envoyées par l'utilisateur.*

### Administration

![Administration des demandes](docs/Administration.png)

*Interface permettant à l'administrateur de traiter les demandes.*

## Technologies utilisées

- **PHP 8.4 ou supérieur**  
- **Symfony 8.1**  
- **Twig** pour les vues  
- **Doctrine ORM** pour communiquer avec la base de données  
- **Symfony Security** pour l'inscription et la connexion  
- **HTML / CSS**  
- **MySQL** pour la base de données  
- **Composer** pour installer les dépendances

## Prérequis

Avant de lancer le projet, il faut disposer de :

- PHP 8.4 ou une version supérieure ;  
- Composer ;  
- un serveur de base de données compatible avec Doctrine ;  
- Git

## Installation

1. **Cloner le dépôt**

git clone \<https://github.com/oznord/BeDoggy>

cd BeDoggy

2. **Installer les dépendances**

composer install

3. **Configurer l'environnement et la base de données** Créer un fichier `.env.local` à la racine du projet (ou copier le `.env`) et configurer l'URL de connexion à la base de données :

DATABASE\_URL="mysql://utilisateur:mot\_de\_passe@127.0.0.1:3306/bedoggy?serverVersion=8.0"

4. **Créer la base de données et appliquer les migrations**

php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

5. **Démarrer le serveur local**

symfony server:start

*Si vous n'avez pas le CLI Symfony, vous pouvez utiliser le serveur PHP natif : `php -S localhost:8000 -t public`*

## Utilisation

### Créer un compte utilisateur

1. Ouvrir la page d'inscription.  
2. Renseigner ses informations personnelles.  
3. Se connecter avec son adresse e-mail et son mot de passe.

### Envoyer une demande

1. Ouvrir la page de création d'une demande.  
2. Sélectionner une ou plusieurs prestations avec les cases à cocher.  
3. Décrire le besoin et renseigner les informations du chien.  
4. Choisir une date souhaitée.  
5. Envoyer la demande.

La demande apparaît ensuite dans la page **Mes demandes** avec son statut.

### Traiter une demande en tant qu'administrateur

Depuis l'espace administrateur, il est possible de consulter les demandes reçues, de modifier leur statut et d'ajouter une réponse visible par le client.

## Structure principale du projet

BeDoggy/

config/           \# Configuration de Symfony et Doctrine

migrations/       \# Migrations de la base de données

public/           \# Point d'entrée et fichiers publics

assets/           \# Images et feuilles de style

src/

Controller/       \# Contrôleurs de l'application

Entity/           \# Entités Doctrine

Repository/       \# Requêtes vers la base de données

Security/         \# Authentification

templates/        \# Vues Twig

## Commandes utiles

Vérifier la syntaxe des templates Twig :

php bin/console lint:twig templates

Vérifier la configuration des routes :

php bin/console debug:router

Vider le cache Symfony :

php bin/console cache:clear

## Limites et améliorations possibles

Le projet constitue une base fonctionnelle. Parmi les évolutions possibles :

- ajouter des tests automatisés plus complets ;  
- améliorer la gestion des rôles et des droits d'accès ;  
- ajouter des notifications par e-mail ;  
- permettre la prise de rendez-vous directement depuis l'application ;  
- améliorer encore l'affichage sur les petits écrans ;  
- ajouter une gestion plus complète des photos des prestations.

## Auteurs

Projet réalisé par **oznord** et **Sonp1ay**.
