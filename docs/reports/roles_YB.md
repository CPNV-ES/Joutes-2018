Prise en charge le 31.10.2019, par Yvann Butticaz

# Analyse du problème
    Gestion des rôles utilisateurs inexistante sur l'application.

## Fonctionnement actuel
    Tout utilisateur classique a le droit de participant. Pour les droit plus élevés, des commandes custom artisan sont utilisées.
    Aucune table de rôle n'est présente dans la base de donnée

## Description du problème
    Une page d'administration des rôles est nécessaire pour une meilleure gestion des roles.

## Description de la solution
    Ajouter une table rôle dans la base de donnée, ajouter une page d'administration générale sur lequel se trouvera un lien à la page d'administration des rôles.
    Sur la page de gestion des rôles, implémenter un CRUD.


# Plan d'intervention

(Terminé, le ...)

1. Implementation d'une table role dans la DB
2. Modification des commandes custom artisan pour correspondre avec la base de donnée, utlisation d'eloquent.
3. 

# Tests

(Terminés, le ...)

# Commit / Merge

(Fait, le ...)

# Revue de code

(Effectuée, le ...)

# Documentation

(Mise à jour, le ...)