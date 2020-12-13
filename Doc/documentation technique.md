# Documentation technique

## A quoi ça sert ?

## Dans quel contexte fonctionne-t-il ?

## Qu'est-ce que je dois faire pour pouvoir l'essayer ?

## Qu'est-ce que je dois faire pour rejoindre l'équipe de développement ?

### Comprendre le squelette (l'architecture)

### Être familier avec certaines technologies

### Mettre en place l'environnement de travail

### Prendre connaissance des conventions de codage

Tout ce qui est de nature technique est rédigé en anglais: code, commentaires, noms de fonction, de fichiers, de variables, de base de données, de champs, ...

Le formatage du code php suit ce [PhP Style Guide](https://gist.github.com/ryansechrest/8138375)

Les fonctions sont précédées d'un bloc de commentaire qui a la forme suivante:

```
/**
* <nomFonction> : à quoi ça sert
* <paramètre1> : qu’est-ce qu’est + type
* <paramètre2> : qu’est-ce qu’est + type
*…
* return : ce que ça renvoie
**/
```

## M'enfin ... ?

Cette section a pour but de rassembler les réponses aux questions que se posera certainement un développeur qui rejoint le groupe ou qui reprend le projet.
Il s'agit ici de questions d'ordre purement technique et dont la réponse implique plusieurs fichiers (dans le cas où un seul fichier est concerné, ce sont les commentaires qui doivent donner l'explication)

### Qu'est-ce que c'est que ce champ 'slug' dans la table 'status' ?

Un slug est un identifiant sous contrôle du code de l’application. Il se situe entre l’id de base de donnée dont on ne peut jamais présumer de la valeur dans le code et la valeur affichée. Exemple: status ‘Ouvert’, qui a un slug ‘open’ et un id 2. Si je veux sélectionner les rapports ouverts, je fait un select « where slug=‘open’ » . Si l’id de l’état ‘open’ est différent dans une autre db => ça marche, si un jour je veux changer le terme visible de « Ouvert » en « Actif » par exemple, je peux le faire en ne changeant que des données. 

Voir [cette référence](https://medium.com/dailyjs/web-developer-playbook-slug-a6dcbe06c284) (parmi tant d'autres)


