# Installation & Configuration — API-REST sur Ubuntu Server 24.04

---

## Sommaire

- [Installation \& Configuration — API-REST sur Ubuntu Server 24.04](#installation--configuration--api-rest-sur-ubuntu-server-2404)
  - [Sommaire](#sommaire)
  - [Vérification des composants](#vérification-des-composants)
    - [Version attendue](#version-attendue)
    - [Commandes de vérification](#commandes-de-vérification)
  - [Clonage du projet](#clonage-du-projet)
    - [Sur Linux](#sur-linux)
      - [Initialisation du projet](#initialisation-du-projet)
    - [Sur Windows](#sur-windows)
      - [Initialisation du projet](#initialisation-du-projet-1)
  - [Configuration JWT](#configuration-jwt)
    - [Linux](#linux)
      - [Activer JWT](#activer-jwt)
      - [Générer les clés RSA](#générer-les-clés-rsa)
      - [Tester la génération d'un JWT](#tester-la-génération-dun-jwt)
    - [Windows](#windows)
      - [Activer JWT](#activer-jwt-1)
      - [Générer les clés RSA](#générer-les-clés-rsa-1)
      - [Tester la génération d'un JWT](#tester-la-génération-dun-jwt-1)
  - [Désactivation du JWT](#désactivation-du-jwt)
  - [Configuration Apache](#configuration-apache)
    - [Linux](#linux-1)
    - [Windows](#windows-1)
  - [Sourcing des données — Dépannage](#sourcing-des-données--dépannage)

---

## Vérification des composants

Avant de commencer, assurez-vous que **Apache2**, **MariaDB**, **PHP** et **Composer** sont présents sur le serveur.

### Version attendue

| Composant | Version |
|-----------|---------|
| XAMPP / PHP | `8.2.x` |

### Commandes de vérification

```bash
sudo systemctl status apache2   # Statut Apache2
sudo systemctl status mariadb   # Statut MariaDB
composer --version               # Version de Composer
php -v                           # Version de PHP
```

> Liens vers les documentations d'installation :
> [Apache2](https://doc.ubuntu-fr.org/apache2) · [MariaDB](https://doc.ubuntu-fr.org/mariadb) · [Composer](https://doc.ubuntu-fr.org/composer) · [PHP](https://doc.ubuntu-fr.org/php)

> [!WARNING]
> - Vérifier dans votre fichier de configuration Apache que vous avez bien `CGIPassAuth On`
> - Vérifier dans votre fichier `php.ini` que l'extension `sodium` est activée
>
> Si ce n'est pas le cas, consultez la section [Configuration Apache](#configuration-apache)

Vérifiez également la présence des fichiers XML :

```bash
php -m | grep xml
```

---

## Clonage du projet

Placez-vous dans le dossier souhaité :
- **Linux** : `/var/www/html/`
- **Windows** : `C:\xampp\htdocs\`

---

### Sur Linux

> [!WARNING]
> Remplacez `<TOKEN>` par votre access token GitLab avant d'exécuter la commande.

```bash
sudo git clone https://gitlab-ci-token:<TOKEN>@gitlab.siovhb.lycee-basch.fr/titouan-goinard/ap32-stages-apirest.git --branch DEV ap32-stages-apirest
```

#### Initialisation du projet

**1. Installer les dépendances**

Rendez-vous dans `ap32-stages-apirest/app` :

```bash
sudo composer install
```

**2. Configurer l'environnement**

Copiez le fichier `.env` et renommez-le en `.env.local` :

```bash
cp .env .env.local
```

Dans `.env.local`, **remplacez** ce bloc :

```dotenv
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
```

**Par** :

```dotenv
DATABASE_URL="mysql://userStages:<MDP>@127.0.0.1:3306/bdStages?serverVersion=10.11.13&charset=utf8"
# DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/
```

> [!WARNING]
> - Vérifiez votre version MySQL avec `mysql --version`
> - Remplacez `<MDP>` par le mot de passe de l'utilisateur

**3. Configurer les droits d'exécution**

```bash
sudo chmod u+x ./bin/console && sudo chmod u+x ./bin/bdd.sh
```

**4. Créer l'utilisateur de base de données**

Connectez-vous à MySQL puis exécutez :

```sql
SOURCE stages_base_user.sql;
```

**5. Charger l'ORM**

Depuis la racine du projet :

```bash
sudo php bin/bdd.sh
```

**6. Insérer les données**

```bash
sudo php bin/console doctrine:query:sql "$(<../db/realisation/stages_insertInto_v2.sql)"
```

> [!INFO]
> En cas d'erreur lors de cette commande, consultez la section [Sourcing des données](#sourcing-des-données--dépannage)

---

### Sur Windows

Ouvrez un terminal (`cmd` dans la barre de recherche, ou clic droit > *Ouvrir dans le Terminal*).

> [!WARNING]
> Remplacez `<TOKEN>` par votre access token GitLab avant d'exécuter la commande.

```bash
git clone https://gitlab-ci-token:<TOKEN>@gitlab.siovhb.lycee-basch.fr/titouan-goinard/ap32-stages-apirest.git --branch DEV ap32-stages-apirest
```

#### Initialisation du projet

**1. Installer les dépendances**

Rendez-vous dans `ap32-stages-apirest/app` :

```bash
composer install
```

**2. Configurer l'environnement**

Copiez `.env` en `.env.local`, puis **remplacez** ce bloc :

```dotenv
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
```

**Par** :

```dotenv
DATABASE_URL="mysql://userStages:<MDP>@127.0.0.1:3306/bdStages?serverVersion=10.11.13&charset=utf8"
# DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/
```

**3. Créer l'utilisateur de base de données**

Connectez-vous à MySQL puis exécutez :

```sql
SOURCE stages_base_user.sql;
```

**4. Charger l'ORM**

```bash
sudo php bin/bdd.sh
```

**5. Insérer les données**

```bash
bin/console doctrine:query:sql "$(<../db/realisation/stages_insertInto_v2.sql)"
```

> [!INFO]
> En cas d'erreur lors de cette commande, consultez la section [Sourcing des données](#sourcing-des-données--dépannage)

---

## Configuration JWT

### Linux

#### Activer JWT

Dans `.env.local`, **remplacez** ce bloc :

```dotenv
###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=ef9505d5e08bb528d4a11b819b307a9f20f4bd16e2cb995d316314e2fb17a35b
###< lexik/jwt-authentication-bundle ###
```

**Par** (passphrase vide) :

```dotenv
###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=
###< lexik/jwt-authentication-bundle ###
```

#### Générer les clés RSA

Placez-vous dans `./config/` puis créez le dossier JWT :

```bash
sudo mkdir jwt && cd ./jwt
```

Générez les clés privée et publique :

```bash
sudo openssl genrsa -out private.pem 2048 && openssl rsa -in ./private.pem -pubout > public.pem
```

Ajustez les droits pour l'utilisateur web :

> [!WARNING]
> Vérifiez que votre utilisateur Apache appartient bien au groupe `www-data` et que ce groupe existe.

```bash
chgrp -R www-data ../jwt/ && chmod -R 640 ../jwt/*
```

#### Tester la génération d'un JWT

```bash
php bin/console lexik:jwt:generate-token nicolas.batauld@lycee-basch.fr --env=prod
```

> Remplacez l'email par un email présent dans votre base de données si nécessaire.

---

### Windows

#### Activer JWT

Même procédure que Linux : dans `.env.local`, videz la `JWT_PASSPHRASE` (voir bloc ci-dessus).

#### Générer les clés RSA

Placez-vous dans `./config/`, créez un dossier `jwt`, puis dans une invite de commande :

```bash
openssl genrsa -out private.pem 2048 && openssl rsa -in ./private.pem -pubout > public.pem
```

> La configuration JWT est terminée sous Windows (pas de gestion des droits de groupe nécessaire).

#### Tester la génération d'un JWT

```bash
php bin/console lexik:jwt:generate-token nicolas.batauld@lycee-basch.fr --env=prod
```

---

## Désactivation du JWT

Pour désactiver l'authentification JWT, modifiez `APP_ENV` dans `.env.local` :

| Valeur de `APP_ENV` | Comportement JWT |
|---------------------|-----------------|
| `dev` | JWT **désactivé** |
| `prod` | JWT **activé** |

---

## Configuration Apache

### Linux

Rendez-vous dans le répertoire Apache :

```bash
cd /etc/apache2/
```

Faites une sauvegarde du fichier de configuration :

```bash
sudo cp apache2.conf apache2.conf.bak
```

Éditez le fichier :

```bash
sudo nano apache2.conf
```

**Remplacez** ce bloc :

```apache
<Directory /var/www/>
    Options Indexes FollowSymLinks
    AllowOverride None
    Require all granted
</Directory>
```

**Par** :

```apache
<Directory /var/www/>
    Options Indexes FollowSymLinks
    AllowOverride None
    Require all granted
    CGIPassAuth On
</Directory>
```

> Redémarrez Apache2 après modification.

---

### Windows

Localisez et sauvegardez votre fichier `apache.conf` ou `httpd.conf`, puis ajoutez la directive suivante :

```apache
<Directory "C:/xampp/htdocs">
    # Transmets l'en-tête Authorization au script PHP
    CGIPassAuth On
```

Vérifiez ensuite votre fichier `php.ini` situé dans `C:\xampp\php\php.ini` et activez l'extension `sodium` si nécessaire.

> Redémarrez Apache2 après modification.

---

## Sourcing des données — Dépannage

En cas d'erreur lors de l'insertion des données, connectez-vous à MySQL manuellement :

```bash
sudo mysql -u root
```

Puis exécutez :

```sql
USE bdStages;
SOURCE ./app/db/realisation/stages_insertInto_v2.sql;
```