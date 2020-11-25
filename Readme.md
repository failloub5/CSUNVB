# Projet CSUNVB

L’objectif du projet est de créer un site web application permettant aux membres du Centre de Secours et d’Urgences du Nord Vaudois et de la Broie (CSU-NVB) de gérer les rapports de contrôles journaliers et hebdomadaires usuels.

Un premier niveau de détail des fonctionnalités du site se trouve dans le [cahier des charges](CdC.md)

Les spécifications exactes sur lesquelles le développement est effectué se trouvent dans les User Stories du [projet sur IceScrum](https://cloud.icescrum.com/p/XCLGRP3/#/planning)

## Mise en place

Voici la marche à suivre pour reproduire l'environnement de développement de cette application

### Pérequis de configuration
- Interpréteur de ligne de commande (CLI) compatible bash. Sur Windows: Cmder, git bash, wsl (mais pas PowerShell)
- Interpréteur php, version 7 au minimum. Vérification avec: `php -v`
- Gestionnaire de dépendances `npm` de nodejs . Vérification avec: `npm -v`
- Service de base de données MySQL actif
- Client SQL: Workbench, HeidiSQL, DataGrip
- Git version 2.19 minimum. Vérification avec: `git --version`

### Procédure

1. Cloner le repository `git clone https://github.com/CPNV-INFO/CSUNVB`
2. `cd CSUNVB`
3. Installer les dépendances: `npm install`
4. Créer la base de données en exécutant le script `doc\SQL\CSU_data.sql`
5. Copier `.const.example.php` en `.const.php` et adapter le contenu de ce fichier aux paramètres locaux de votre environnement
6. Lancer un serveur web dont la racine est le dossier `public`
