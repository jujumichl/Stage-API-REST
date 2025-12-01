# Guide de configuration du serveur de recette US24 pour AP32
**Objectif** : Mettre en place un serveur de recette US24 pour tester l'API-REST de gestion des stages.

**Ressources** : 
- sous VMWare, le modèle de VM us24_web hébergeant Ubuntu Server 24.04 avec les services ssh, apache2 / php8.3 et mariadb activés, ainsi que l'application web phpmyadmin.

## Création de la VM us24_ap32 sous VMWare
Le serveur us24_ap32 correspondra à une machine virtuelle hébergée sur la suite VSphere.

Procédure à suivre :

- Demander à accéder au vcenter en saisissant l'URL [https://352009u-srv-vct.352009u.local](https://352009u-srv-vct.352009u.local) 
- Se connecter sous vcenter en renseignant votre login et mot de passe du domaine local
- Ouvrir l'inventaire et se déplacer dans `CD1 / VM-ELEVES / Modeles`
- Créer une VM ayant pour nom `VotreNom_us24_ap32` à partir du modèle `modele_us24_web`
- Choisir l'emplacement `VM_ELEVES/SIO2_2526/SLAM/VotreNom` pour le stockage de la nouvelle VM
- Demander ensuite à modifier les paramètres de la VM pour sélectionner 1GO pour la RAM et VLAN SIO2 SLAM pour l'adaptateur réseau.
- Démarrer la VM et se connecter sous le nom stssio / stssio.

## Configuration de la VM us24_ap32
### Passer en mode d'adressage IP statique
L'objectif est de pouvoir atteindre le serveur `us24_ap32` des postes du réseau SIO et vice-versa. 

Le mode d'accès réseau sous VMWare équivalent au mode pont sous Vbox correspond à l’adaptateur réseau VLAN_SIO2_SLAM. 

Les VMS Linux sous VMWare affectent le nom `ens160` et non `enp0s3` à la carte réseau. 

- Passer la configuration réseau IP de votre serveur us24_ap32 en mode statique sur l'adresse IP 100.115.29.x / 23, x étant le numéro de la 2ème adresse IP du groupe de 5 adresses IP qui vous a été attribué. Les groupes sont notés dans le fichier `IPfixe_2025_SIO.xls` sous Triskell. _Ne pas oublier la configuration de la passerelle et du serveur DNS._

- Vérifier la communication réseau IP entre la machine hôte et le serveur `us24_ap32` et ceci dans les 2 sens. 
- Vérifier le service routage en pingant par exemple l'adresse `1.1.1.1`.
- Vérifier que la résolution de noms soit bien opérationnelle, en particulier pour l'hôte `archive.ubuntu.com`.

### Vérifier les services Apache2 et MariaDB
- Vérifier que le service Apache2 soit installé, puis bien démarré.
- Vérifier que le service MariaDB soit installé, puis bien démarré.

### Vérifier l'interpréteur php et composer
- Demander à voir la version courante de composer.

### Vérifier la version de php et les extensions nécessaires pour Symfony
Comme la version 7 de Symfony impose la version 8.2 de Symfony, demander à voir la version courante l'interpréteur php.

Vérifier également que l'extension xml de l'interpréteur php soit bien activée par la commande suivante :
```
php -m 
```
### Créer un compte sous MariaDB pour gérer la BD
- Via la commande en ligne mysql, créer un compte MariaDB de nom `userStages@localhost` et de mot de passe `secret`.
- Lui donner tous les droits sur le serveur MariaDB.
- Vérifier la connexion du compte `userStages` et ses droits d'accès pour créer une base de données.

### Vous authentifier sur la plateforme Gitlab
Nous utiliserons ici une authentification par jeton limitée au projet ap32-stages-apirest.

- Sous votre projet gitlab, se positionner sous Settings / Access tokens.
- Y créer un nouveau jeton nommé NomEtudiant_Recette ayant le rôle Reporter et la permission read_repository

Ce jeton sera ensuite utilisé dans une URL de type https ainsi formée :
`https://gitlab-ci-token:<votrejeton>@gitlab.siovhb.lycee-basch.fr/votreEspaceNom/votredepot.git`
 
### Récupérer votre branche du dépôt gitlab de groupe
De manière identique à la récupération du dépôt sous xampp de W11, vous allez "descendre" votre branche du dépôt sous le répertoire de publication du site web par défaut d'apache2.

- Se positionner sous le répertoire de publication du site web par défaut `/var/www/html`.
- Demander à cloner votre branche du dépôt de groupe par la commande :
```bash
sudo git clone URLhttpsVotreDepot --branch nomBranche nomRepertoire
```
L'argument `nomRepertoire` est à utiliser si vous souhaitez récupérer le dépôt sous un nom de répertoire différent de celui de votre dépôt sous Gitlab. On supposera par la suite que ce nouveau répertoire se nomme `ap32-stages-apirest`.

- Suite au clonage, vérifier la présence et le contenu répertoire `ap32-stages-apirest`.
- Se positionner dans ce nouveau répertoire, et vérifier que votre branche est active.

### Installer Symfony et configurer votre application pour la production
- Lancer la commande suivante pour installer les composants de symfony sous `vendor` :
```bash
sudo composer install  
```
- Copier le fichier `.env` dans un fichier `.env.local`.
- Adapter la ligne suivante dans le nouveau fichier `.env.local` suivant vos données de configuration et la version de votre serveur MariaDB : 
```php
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
```

### Créer la base de données
- Via la console de symfony, créer la base de données stages et son schéma.
- Via la console de symfony ou la commande mysql, y importer le jeu d'essai `stages_insertinto_v2.sql`.
  
### Accéder à l'API-REST hébergée sur votre serveur de recette
- Sur le serveur us24_ap32 lui-même, vérifier par la commande `curl` le bon accès à l'API-REST pour obtenir la liste complète de vos ressources, organisations, contacts ou stages.
- Sur la machine hôte, invoquer l'API-REST sous votre navigateur favori. 
- Sous Talend API-Tester, créer un nouvel environnement nommé `GestionStages-Recette` correspondant à votre serveur de recette, et y retester vos requêtes http opérationnelles sous votre environnement de développement.

Si l’API-REST ne fonctionne pas, voici une première checklist des points à vérifier : 

- Vérifier le contenu des logs d’Apache `/var/log/apache2/error.log`
- Dans le fichier `.env.local`, vérifier les valeurs des paramètres de connexion à la base de données. Ils doivent être ajustés à la casse près.
- Vérifier la présence et le contenu du répertoire `vendor`

## Sécuriser l'environnement d'exécution en production
L'emplacement actuel de l'API-REST permet d'accéder au sous-répertoire `public` et par là-même au fichier `index.php`, contrôleur principal de l'API-REST. Cependant, il est aussi possible d'accéder aux autres sous-répertoires à moins qu'ils ne soient bloqués par une directive telle que `Deny from all`, ce qui est le cas pour le sous-répertoire app, mais pas vendor, ni test, ni .git.

Il apparaît donc intéressant de fournir une URL `ap32` référençant directement un sous-répertoire qui se trouvera dans un sous-répertoire de `/var/www`, et non plus sous `/var/www/html`.

Voici la procédure à suivre :

1. Déplacer le sous-répertoire `/var/www/html/ap32-stages-apirest` et son contenu sous le répertoire `/var/www`.
2. Vérifier et réappliquer si besoin l'appartenance du répertoire `/var/www/ap32-stages-apirest` et de son contenu au groupe `www-data` ainsi que le droit d'écriture du sous-répertoire `var` et de son contenu au groupe `www-data`
4. Ajouter les lignes suivantes en fin de directive `VirtualHost` dans le fichier `/etc/apache2/sites-available/000-default.conf`
```bash
Alias /ap32 /var/www/ap32-stages-apirest/app/public
<Directory "/var/www/ap32-stages-apirest/app/public">
    AllowOverride all
    Require all granted
</Directory>
```
1. Redémarrer le service apache2
2. Ajuster les variables d'environnement `GestionStages-Recette` sous Talend, puis repasser quelques tests sur l'API-REST ainsi hébergée
3. Vérifier l'impossibilité d'accéder au répertoire `.git` de votre espace.