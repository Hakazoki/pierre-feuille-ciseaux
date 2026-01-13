# Projet Shifumi 

Cette application web permet de jouer au **Shifumi** contre un ordinateur, avec une version classique et une version avancée **Pierre / Feuille / Ciseaux / Lézard / Spock**.

---

## Objectifs du projet

- Appliquer les bases du **PHP 8**
- Implémenter la logique du jeu Shifumi
- Utiliser les **sessions**
- Stocker des données dans une **base de données MySQL**
- Travailler avec **Git / GitHub**
- Concevoir une interface **responsive et ergonomique**

---

## Technologies utilisées

- HTML
- CSS  
- PHP  
- MySQL  
- Bootstrap  

---

## Fonctionnalités

### Jeu
- Mode **Classique** : Pierre / Feuille / Ciseaux
- Mode **Spock** : Pierre / Feuille / Ciseaux / Lézard / Spock
- Affichage du résultat à chaque tour
- Historique des coups joués
- Bouton de redémarrage de partie

### Intelligence artificielle
- IA **aléatoire**
- IA **logique** basée sur les coups précédents

### Statistiques
- Victoires du joueur
- Défaites de l’ordinateur
- Égalités
- Nombre de tours
- Winrate

### Utilisateur
- Inscription
- Connexion / Déconnexion
- Gestion de session
- Classement Top 10

---

## Base de données

- Structure fournie dans le fichier `shifumi.sql`
- Données enregistrées :
  - Adresse IP
  - Nombre de tours joués
  - Nombre de victoires
  - Timestamp
  - Taux de réussite

---

## Installation
```bash
1. Cloner le dépôt :

git clone <url-du-repo>

2. Importer la base de données :

Importer le fichier shifumi.sql dans MySQL

3. Configuration :

Copier config.example.php en config.php

Renseigner les identifiants de la base de données

4. Lancer le projet :

Via un serveur local (WAMP)

Accéder à l’application :
http://localhost/shifumi/index.php

Sécurité

Mots de passe hashés

Requêtes SQL sécurisées 

Fichier config.php ignoré par Git

Interface

Design responsive

Compatible mobile, tablette et ordinateur

Interface moderne et intuitive

