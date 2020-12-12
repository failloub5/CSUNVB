# Projet CSUNVB

L’objectif du projet est de créer un site web application permettant aux membres du Centre de Secours et d’Urgences du Nord Vaudois et de la Broie (CSU-NVB) de gérer les rapports de contrôles journaliers et hebdomadaires usuels.

Un premier niveau de détail des fonctionnalités du site se trouve dans le [cahier des charges](doc/CdC.md)

Les spécifications exactes sur lesquelles le développement est effectué se trouvent dans les User Stories des projets IceScrum (détails ci-dessous)

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
4. Créer la base de données en exécutant le script `doc\SQL\CSU_data.sql`. Ce script contient plusieurs utilisateurs dont le mot de passe est leurs initiales (exemple: login 'JDE', mot de passe 'JDE')
5. Copier `.const.example.php` en `.const.php` et adapter le contenu de ce fichier aux paramètres locaux de votre environnement
6. Lancer un serveur web dont la racine est le dossier `public`

## Modalités de travail

### Repository

Le repository a quatre branches:

1. `master` sous la responsabilité du Product Owner (Xavier)
2. `stups` contenant les développement spécifiques au rapports de stupéfiants, gérée par Loïc, Alexandre D et Jérémy
2. `todo` contenant les développement spécifiques au rapports de tâches hebdomadaires, gérée par Vicky, Daniel et Alexandre R
2. `garde` contenant les développement spécifiques au rapports de remise de garde, gérée par Paola et Michaël

Aucune autre branche ne doit être créée sans concertation préalable avec l'ensemble de l'équipe.

Chaque équipe travaille sur sa branche. Xavier est responsable de fusionner le tout sur `master` et de refléter le tout sur chacune des branches ensuite

### IceScrum

Le suivi des projets se fait dans les projets IceScrum suivants:

1. [Stups](https://cloud.icescrum.com/p/XCLGRP3/#/planning)
1. [Garde](https://cloud.icescrum.com/p/XCLGRP1/#/planning)
1. [Todo](https://cloud.icescrum.com/p/XCLGRP2/#/planning)

Chaque développeur(se) veillera à toujours travailler sur une tâche de son projet IceScrum. Il/elle veillera également à reporter dans le champ `time spent` de la tâche le nombre de minutes approximativement passées à la réalisation de la tâche.

Le journal de travail de chacun(e) pourra ainsi être automatiquement construit grâce aux pages `doc\timesheets-xxx\Timesheet-individual.html`

### Teams

Le document 'Q&R.docx' dans l'équipe CSUNVB sur Teams permet à chaque membre de l'équipe de poser une question à un autre membre, et surtout de garder une trace de la réponse
