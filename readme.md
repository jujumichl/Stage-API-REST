# Documentation installation et configuration API-REST sur serveur de recette Ubuntu Server 24.04

## Vérification des composants

Sur le serveur de recette, la présence de Apache2, MariaDB, PHP et Composer est obligatoire pour importer le projet.

* Pour voir l'existence et le statut de Apache2 : `sudo systemctl status apache2`
* Pour voir l'existence et le statut de MariaDB : `sudo systemctl status mariadb`
* Pour voir la version de Composer : `composer --version`
* Pour voir la version de PHP : `php -v`

En cas de manque, veuillez suivre ces documentations : 

* [Documentation de Apache2](https://doc.ubuntu-fr.org/apache2)
* [Documentation de MariaDB](https://doc.ubuntu-fr.org/mariadb)
* [Documentation de Composer](https://doc.ubuntu-fr.org/composer)
* [Documentation de PHP](https://doc.ubuntu-fr.org/php)

>[!Warning]
> Vérifier dans votre fichier de configuration apache que vous avez bien `CGIPassAuth On`.  
> Sinon [cliquez ici](#apache-conf) 

Dans le fichier conf de Apache2 : `cd /etc/apache2/`, éditez le fichier `apache2.conf` avec la commande `sudo nano apache2.conf` et ajoutez à la fin de la directive ce code :
```bash
<Directory "C:/xampp/htdocs"> :
    # Transmits Authorization header to PHP script
    CGIPassAuth On
```

Une fois que tout est fonctionnel, vérifier la présence des fichiers XML avec cette commande : `php -m | grep xml`

## Clonage du projet 
Se placer dans le dossier souhaité pour le clonage (nous recommandons /var/www/html/ ou C:\\xampp\\htdocs\\).
### Sur Linux
>[!WARNING]
> L'URL fournie dans la commande doit être modifiée.
> Remplacer `<TOKEN>` par votre access token.


```bash
sudo git clone https://gitlab-ci-token:<TOKEN>@gitlab.siovhb.lycee-basch.fr/titouan-goinard/ap32-stages-apirest.git --branch DEV ap32-stages-apirest 
```

#### Initialisation du projet :
Rendez-vous dans `ap32-stages-apirest/app` puis installez les dépendances `sudo composer install`, ensuite copier votre `.env` et renommez-le en `.env.local`, vous modifierez ce bloque `.env.local` :
```powershell
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
```
Par :
```powershell
DATABASE_URL="mysql://userStages:<MDP>@127.0.0.1:3306/bdStages?serverVersion=10.11.13&charset=utf8"
# DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/
``` 
>[!Warning]
> Vérifiez que vous avez la même version de MySQL avec la commande `mysql --version`
>
> N'oubliez pas de changer le `<MDP>` avec le mot de passe de l'utilisateur

Nous allons ensuite changer les droits sur les fichiers bdd.sh et console en leur ajoutant le droit d'exécution.
```bash
sudo chmod u+x ./bin/console && sudo chmod u+x ./bin/bdd.sh
```

Enfin nous allons insérer notre jeu de données dans notre base de données
```bash
 sudo bin/console doctrine:query:sql "$(<../db/realisation/stages_insertInto_v2.sql)"
``` 

> [!info]
> Si la commande précédente vous a générer une erreur veuillez Cliquer [ici](#sourcing-des-donnees)

### Sur Windows
Ouvrez un interpréteur de commande en tapant `cmd` dans la barre de recherche Windows ou rendez-vous à l'endroit de travail souhaité et effectuer un clic droit et faites `ouvrir dans le Terminal`.

>[!WARNING]
> L'URL fournie dans la commande doit être modifiée.
> Remplacer `<TOKEN>` par votre access token
```bash
git clone https://gitlab-ci-token:<TOKEN>@gitlab.siovhb.lycee-basch.fr/titouan-goinard/ap32-stages-apirest.git --branch DEV ap32-stages-apirest 
```

#### Initialisation du projet :
Rendez-vous dans `ap32-stages-apirest/app` puis installez les dépendances `composer install`, ensuite copiez votre `.env` et renommez-le en `.env.local`, vous modifierez ce bloc :
```powershell
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
```
Par :
```powershell
DATABASE_URL="mysql://userStages:<MDP>@127.0.0.1:3306/bdStages?serverVersion=10.11.13&charset=utf8"
# DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/
``` 
### JWT
#### Activer JWT 
Aller dans le fichier `.env.local` et modifier le bloc suivant : 
```powershell
###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=ef9505d5e08bb528d4a11b819b307a9f20f4bd16e2cb995d316314e2fb17a35b
###< lexik/jwt-authentication-bundle ###
```
Par ce bloc :
```powershell
###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=
###< lexik/jwt-authentication-bundle ###
```
L'étape suivante va être la création du jeu de clés RSA, en créant par la clé privée/publique puis on va extraire la clé publique. Voici la démarche à suivre :
* Se mettre en utilisateur `sudoer` avec la commande `sudo su`
* Se placer dans le dossier `./config/` puis écrire ceci :
```bash 
mkdir jwt && cd ./jwt
```
```bash
openssl genrsa -out private.pem 2048 
&& 
openssl rsa -in ./private.pem -pubout > public.pem
```
Les droits sur les clés privées et publiques ne permettent pas à l'utilisateur web d'utiliser ces clés, nous devons donc changer les droits (tout d'abord le groupe puis les droits du groupe)
>[!Warning]
> Vérifiez que votre utilisateur Apache est bien dans le groupe `www-data` et que ce groupe existe.

```bash
chgrp -R www-data ../jwt/ 
&& 
chmod -R 640 ../jwt/*
```

>[!Warning]
> Vérifiez que vous avez la même version de MySQL avec la commande (toujours dans l'invite de commande) `mysql --version`
>
> N'oubliez pas de changer le `<MDP>` avec le mot de passe de l'utilisateur

Enfin nous allons insérer notre jeu de données dans notre base de données
```bash
 bin/console doctrine:query:sql "$(<../db/realisation/stages_insertInto_v2.sql)"
``` 
#### Désactivation du JWT 
Pour désactiver l'authentification par `JWT`, il faut changer dans le `.env.local` le `APP_ENV=`, dans l'environnement de `dev` l'authentification JWT est `désactivée`, cependant dans l'environnement de `prod` l'authentification JWT est activée.

## Apache conf
Se rendre dans le fichier apache `cd /etc/apache2/`, faire une copie du fichier `apache.conf` avec la commande `sudo cp apache2.conf apache2.conf.bak`.

Une fois la copie faite, il faut l'éditer :
```bash 
sudo nano apache2.conf
```

Une fois dans le nano du fichier `.conf`, remplacez le bloc suivant :  

```bash 
<Directory /var/www/> 
  Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
</Directory>
```
par ce bloc :

```bash
<Directory /var/www/> 
  Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
   CGIPassAuth On
</Directory>
```
> Redémarrer apache2

## Sourcing des données
En cas d'erreur lors du sourcing des données, effectuées ces commandes ci dessous : 
```bash
# Connexion a mysql 
sudo mysql -u root
``` 

```sql
-- Sourcing des données
use bdStages;
source ./app/db/realisation/stages_insertInto_v2.sql;
```
