## Obtenir tous les stages
### URI
```plaintext
GET /stages
```

### Paramètres d'URL
Aucun

### Réponse
En cas de succès, retourne [`<200>`](./api.md#codes-status) et la réponse suivante :

| Propriété                | Type           | Description                   |
|--------------------------|----------------|-------------------------------|
| `message`                | string         | Ok                            |
| `data`                   | Object array   | Tableau d'objets stage        |

Les stages seront triés de manière croissante sur l'id.

Chaque objet stage présente les propriétés suivantes :
```json
{
    "id": entier,
    "descriptifMission": string,
    "moyens": string,
    "etudiant": object etudiant,
    "periode": object periode,
    "organisation": object organisation
}
```
Un objet organisation est visible [ici](./api-organisations.md#un-objet-organisation).

Un objet etudiant présente les propriétés suivantes :
```json
{
    "id": entier,
    "nom": string,
    "rue": string,
    "codePostal": string,
    "ville": string,
    "tel": string,
    "email": string,
    "specialite": object specialite
}
```
Un objet specialite présente les propriétés suivantes :
```json
{
    "id": string,
    "sigle": string,
    "intitule": string,
}
```
Un objet periode présente les propriétés suivantes :
```json
{
    "id": entier,
    "dateDebut": string,
    "dateFin": string,
    "numAnneeFormation": entier,
}
```
### Requête exemple avec succès - code statut 200
```shell
curl --url "http://host/path/stages"
```

Fournit une réponse http avec code statut 200 et le corps de réponse json suivant :
```json
{
  "message": "OK",
  "data": [
    {
        "id": 250,
        "descriptifMission": "Réalisation application web de suivi des contrats de maintenance logicielle",
        "moyens": "HTML/CSS/PHP/MariaDB/Gitlab ",
        "etudiant" :
        {
            "id": 80,
            "nom": "Bellec",
            "prenom": "Sylvain",
            "specialite": "Développeur d'applications"
        },
        "periode" :
        {
            "dateDebut": "2024-01-06",
            "dateFin": "2024-02-14",
            "numeroAnneeFormation": 2
        },
        "organisation":
        { 
            "id": 51,
            "nom": "Groupe Avril",
            "rue": "...",

            "urlSiteWeb": "...",
            "orgaAssurance": "...",
            "numAssurance": "..."
       }
    },
    {
        "id": 255,
        "descriptifMission": "Réalisation d’un module Drupal de gestion",
        "moyens": "HTML/CSS/PHP/MariaDB/Gitlab ",
        "etudiant" :
        {
            "id": 60,
            "nom": "Yigit",
            "prenom": "Abdul",
            "specialite": "Développeur d'applications"
        },
        "periode" :
        {
            "dateDebut": "2024-01-06",
            "dateFin": "2024-02-14",
            "numeroAnneeFormation": 2
        },
        "organisation":
        { 
            "id": 1,
            "nom": "Rectorat de Rennes",
            "rue": "...",

            "urlSiteWeb": "...",
            "orgaAssurance": "...",
            "numAssurance": "..."
        }
    },
    {
    },
    ...
    ] 
}
```

## Obtenir un stage spécifié par son id
### URI
```plaintext
GET /stages/:id
```

### Paramètres
| Propriété                | Type           | Description                   |
|--------------------------|----------------|-------------------------------|
| :id                      | entier         | id du stage                   |

### Réponse
En cas de succès, retourne [`<200>`](./api.md#codes-status) et la réponse suivante :

| Propriété                | Type           | Description                   |
|--------------------------|----------------|-------------------------------|
| `message`                | string         | Ok                            |
| `data`                   | Object         | Objet stage                   |

Un objet stage présente les propriétés suivantes :
```json
{
    "id": entier,
    "descriptifMission": string,
    "moyens": string,
    "etudiant": object,
    "periode": object,
    "organisation": object
}
```
### Requête exemple avec succès - code statut 200
```shell
curl --url "http://host/path/stages/250"
```

Fournit une réponse http avec code statut 200 et le corps de réponse json suivant :
```json
{
    "message": "OK",
    "data": 
    {
        "id": 250,
        "descriptifMission": "Réalisation application web de suivi des contrats de maintenance logicielle",
        "moyens": "HTML/CSS/PHP/MariaDB/Gitlab ",
        "etudiant" :
        {
            "id": 80,
            "nom": "Bellec",
            "prenom": "Sylvain",
            "specialite": "Développeur d'applications"
        },
        "periode" :
        {
            "dateDebut": "2024-01-06",
            "dateFin": "2024-02-14",
            "numeroAnneeFormation": 2
        },
        "organisation":
        {     
            "id": 51,
            "nom": "Groupe Avril",
            "rue": "...",

            "urlSiteWeb": "...",
            "orgaAssurance": "...",
            "numAssurance": "..."
        }
    }
}
```
### Requête exemple avec échec - code statut 404
Le code statut 404 est retourné si l'id de stage demandé est inexistant.

```shell
curl --url "http://host/path/stages/5000"
```

Fournit une réponse http avec code statut 404 et le corps de réponse json suivant :

```json
{
    "message": "Ressource inexistante",
}
```
### Requête exemple avec échec - code statut 400
Le code statut 404 est retourné si l'id de stage demandé est invalide.

```shell
curl --url "http://host/path/stages/5ab0"
```

Fournit une réponse http avec code statut 400 et le corps de réponse json suivant :

```json
{
    "message": "Id de ressource invalide",
}
```
## Créer un nouveau stage
### URI
```plaintext
POST /stages
```

### Paramètres d'URL
Aucun
### Données du payload - passées dans le corps de la requête au format JSON
| Propriété           | Type          | Description                                                      |
|---------------------|---------------|------------------------------------------------------------------|
| descriptifMission   | string        | descriptif de la mission                                         |
| moyens              | string        | moyens : équipements, systèmes, méthodes, outils                 |
| idEtudiant          | integer       | numéro de l'étudiant effectuant le stage                         |
| idOrganisation      | integer       | numéro de l'organisation accueillant l'étudiant                  |
| idPeriodeStage      | integer       | id de la période du stage                                        |

Toutes les propriétés ci-dessus doivent être présentes dans le payload.

### Réponse
En cas de succès, retourne [`<200>`](./api.md#codes-status) et la réponse suivante :

| Propriété                | Type           | Description                                         |
|--------------------------|----------------|-----------------------------------------------------|
| `message`                | string         | Stage d'id x a été créé                             |
| `data`                   | Object data    | Fournit des données sur la nouvelle ressource       |

L'objet data comporte les propriétés suivantes :

| Propriété                | Type           | Description                                         |
|--------------------------|----------------|-----------------------------------------------------|
| `_selfLink`              | string         | Lien absolu vers la nouvelle ressource              |

### Requête exemple avec succès - code statut 201
```shell
curl --url "http://host/path/stages" --request POST --header "Content-Type: application/json"
     --data "{\"idEtudiant\":20, 
              \"idOrganisation\": 25,
              \"idPeriodeStage\": 10,
              \"descriptifMission\": \"Ecriture de tests IHM automatisés Espresso d’une application mobile\",
              \"moyens\": \"Java / Espresso / Gitlab\"}"
```

Fournit une réponse http avec code statut 201 et le corps de réponse json suivant :
```json
{
    "message": "Stage d'id 300 créé",
    "data": {
        "_selfLink": "http://host/path/stages/300",
    }
}
```
### Requête exemple avec échec - code statut 400 - Données non renseignées
Le code statut 400 est retourné si les données du payload sont invalides.

```shell
curl --url "http://host/path/stages" --request POST --header "Content-Type: application/json"
     --data "{\"idEtudiant\": 20, 
              \"idOrganisation\": 10
            }"
```

Fournit une réponse http avec code statut 400 et le corps de réponse json suivant :
```json
{
    "message": "Les données fournies sont erronées",
    "erreurs" : ["Descriptif mission non renseigné", "Moyens non renseignés"]
}
```
### Requête exemple avec échec - code statut 400 - Id étudiant invalide
Le code statut 400 est retourné si les données du payload sont invalides.

```shell
curl --url "http://host/path/stages" --request POST --header "Content-Type: application/json"
     --data "{\"idEtudiant\":"AB", 
              \"idOrganisation\": 25,
              \"idPeriodeStage\": 10,
              \"descriptifMissions\": \"Ecriture de tests IHM automatisés Espresso d’une application mobile\",
              \"moyens\": \"Java / Espresso / Gitlab\"}"
```

Fournit une réponse http avec code statut 400 et le corps de réponse json suivant :
```json
{
    "message": "Les données fournies sont erronées",
    "erreurs" : ["Id étudiant invalide"]
}
```
### Requête exemple avec échec - code statut 400 - Période de stage inexistante
Le code statut 400 est retourné si les données du payload sont invalides.

```shell
curl --url "http://host/path/stages" --request POST --header "Content-Type: application/json"
     --data "{\"idEtudiant\":20, 
              \"idOrganisation\": 25,
              \"idPeriodeStage\": 500,
              \"descriptifMission\": \"Ecriture de tests IHM automatisés Espresso d’une application mobile\",
              \"moyens\": \"Java / Espresso / Gitlab\"}"
```

Fournit une réponse http avec code statut 400 et le corps de réponse json suivant :
```json
{
    "message": "Les données fournies sont erronées",
    "erreurs" : ["Période de stage inexistante"]
}
```