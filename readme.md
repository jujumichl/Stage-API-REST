# Cahier des charges[🌐](./docs/cdc.md)

# Documentation API-REST des ressources organisations, stages et contacts[🌐](./docs/api.md)

# Documentation installation et configuration API-REST sur serveur de recette Ubuntu Server 22.04[🌐](./docs/vm-us22web-config.md)

# Bonnes pratiques sur la structure de la base de données[🌐](./docs/db-guidelines.md)

# Installation API-REST sur poste de travail ou serveur de test
1. Récupérer le dépôt à l'emplacement souhaité par la commande :
```bash
git clone urlDepot
```
2. Télécharger les composants Symfony référencés dans le fichier `composer.json` :
```bash
composer install
```
Les composants installés sont ceux du projet `symfony/skeleton` + `symfony/orm-pack` + `symfony/serializer-pack` + `symfony/maker-bundle` en mode `dev`.

La directive `naming_strategy`du fichier `config\packages\doctrine.yaml`a été positionnée à `doctrine.orm.naming_strategy.default` pour que les noms d'entités avec plusieurs mots soient laissés en minuscules sans caractère underscore entre les 2 mots.

3. Sous Linux, rendre le sous-répertoire `var` accessible en écriture pour le compte Linux utilisé par votre serveur web.
4. Copier le fichier `.env` dans un nouveau fichier `.env.local`.
5. Dans ce nouveau fichier `.env.local`, adapter la variable d'environnement suivante :
```php
DATABASE_URL
```
6. Créer la base de données par la commande : `php bin/console doctrine:database:create`
7. Créer le schéma de base de données par la commande : `php bin/console doctrine:schema:update --force`
8. Importer la base de données via le script sql du répertoire `db/realisation/stages_insertinto_v2.sql`