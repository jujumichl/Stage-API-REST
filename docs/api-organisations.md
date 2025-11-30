## Obtenir toutes les organisations
### URI
```plaintext
GET /organisations
```

### Paramètres d'URL
Aucun

### Réponse
En cas de succès, retourne [`<200>`](./api.md#codes-status) et la réponse suivante :

| Propriété                | Type           | Description                   |
|--------------------------|----------------|-------------------------------|
| `message`                | string         | Ok                            |
| `data`                   | Object array   | Tableau d'objets organisation |

Les organisations seront triées de manière croissante sur le nom.

#Un objet organisation présente les propriétés suivantes :
```json
{
    "id": entier,
    "nom": string,
    "categorie": Object categorie,
    "rue": string,
    "codePostal": string,
    "ville": string,
    "tel": string,
    "email": string,
    "urlSiteWeb": string,
    "orgaAssurance": string,
    "numAssurance": string,
}
```
Un objet categorie présente les propriétés suivantes :
```json
{
    "id": entier,
    "libelle": string
}
```

### Requête exemple avec succès - code statut 200

```shell
curl --url "http://host/path/organisations"
```

Fournit une réponse http avec code statut 200 et le corps de réponse json suivant :
```json
{
  "message": "OK",
  "data": [
    {
      "id": "1",
      "nom": "Rectorat de Rennes",
      "categorie": {
            "id": 1,
            "libelle": "Administrations, collectivités territoriales"
      },
      "rue": "96 rue d'Antrain",
      "codePostal": "35000",
      "ville": "Rennes",
      "tel": "0223217777",
      "email": NULL,
      "urlSiteWeb": NULL,
      "orgaAssurance": NULL,
      "numAssurance": NULL,
    },
    {
      "id": "2",
      "nom": "IRISA",
      ...
      "email": NULL,
      ...
    },
    ...
    ] 
}
```

## Obtenir une organisation spécifiée par son id

### URI
```plaintext
GET /organisations/:id
```

### Paramètres
| Propriété                | Type           | Description                   |
|--------------------------|----------------|-------------------------------|
| :id                      | integer        | id de l'organisation          |

### Réponse
En cas de succès, retourne [`<200>`](./api.md#codes-status) et la réponse suivante :

| Propriété                | Type           | Description                   |
|--------------------------|----------------|-------------------------------|
| `message`                | string         | Ok                            |
| `data`                   | Object         | Objet organisation            |

Un objet organisation présente les propriétés suivantes :
```json
{
    "id": entier,
    "nom": string,
    "categorie": Object categorie,
    "rue": string,
    "codePostal": string,
    "ville": string,
    "tel": string,
    "email": string,
    "urlSiteWeb": string,
    "nomAssurance": string,
    "numeroContratAssurance": string,
}
```
Un objet categorie présente les propriétés suivantes :
```json
{
    "id": entier,
    "libelle": string
}
```

### Requête exemple avec succès - code statut 200
```shell
curl --url "http://host/path/organisations/1"
```

Fournit une réponse http avec code statut 200 et le corps de réponse json suivant :
```json
{
    "message": "OK",
    "data": {
        "id": "1",
        "nom": "Rectorat de Rennes",
        "categorie": {
              "id": 1,
              "libelle": "Administrations, collectivités territoriales"
        },
        "rue": "96 rue d''Antrain",
        "codePostal": "35000",
        "ville": "Rennes",
        "tel": "0223217777",
        "email": NULL,
        "urlSiteWeb": NULL,
        "nomAssurance": NULL,
        "numeroContratAssurance": NULL
    }
}
```
### Requête exemple avec échec - code statut 404
Le code statut 404 est retourné si le numéro d'organisation demandé est inexistant.

```shell
curl --url "http://host/path/organisations/1000"
```

Fournit une réponse http avec code statut 404 et le corps de réponse json suivant :

```json
{
    "message": "Ressource inexistante",
}
```
### Requête exemple avec échec - code statut 400
Le code statut 400 est retourné si l'id d'organisation demandé est invalide.

```shell
curl --url "http://host/path/organisations/1ag"
```

Fournit une réponse http avec code statut 400 et le corps de réponse json suivant :

```json
{
    "message": "Id de ressource invalide",
}
```
## Mettre à jour les données d'une organisation spécifiée par son id
### URI
```plaintext
PUT /organisations/:id
```

### Paramètres d'URL
| Attribut          | Type           | Description                   |
|-------------------|----------------|-------------------------------|
| :id               | integer        | id de l'organisation          |

### Données du payload - passées dans le corps de la requête au format JSON
| Attribut          | Type          | Description                   |
|-------------------|---------------|-------------------------------|
| rue               | string        | adresse de l'organisation     |
| codePostal        | string        | son code postal               |
| ville             | string        | sa ville                      |
| tel               | string        | son téléphone                 |
| email             | string        | son email                     |
| urlSiteWeb        | string        | son url de site web           |

Au moins un attribut parmi les 6 doit figurer dans le payload.

### Réponse
En cas de succès, retourne [`<200>`](./api.md#codes-status) et la réponse suivante :

| Propriété                | Type           | Description                                         |
|--------------------------|----------------|-----------------------------------------------------|
| `message`                | string         | Organisation d'id :id a été modifiée                |
| `data`                   | Object data    | Fournit des données sur la ressource  modifiée      |

L'objet data comporte les propriétés suivantes :

| Propriété                | Type           | Description                                         |
|--------------------------|----------------|-----------------------------------------------------|
| `_selfLink`              | string         | Lien absolu vers la ressource modifiée              |

### Requête exemple avec succès - code statut 200
```shell
curl --url "http://host/path/organisations/1" --request PUT --header "Content-type: application/json"
     --data {"email" : "contact@organisation.fr"}
```

Fournit une réponse http avec code statut 200 et le corps de réponse json suivant :
```json
{
    "message": "Organisation d'id 1 a été modifiée",
    "data": {
        "_selfLink": "http://host/path/organisations/1",
    }
}
```
### Requête exemple avec échec - code statut 404
Le code statut 404 est retourné si l'id d'organisation demandé est inexistant.

```shell
curl --url "http://host/path/organisations/1000" --request PUT --header "Content-Type: application/json"
     --data "{\"email\" : \"contact@organisation.fr\"}"
```

Fournit une réponse http avec code statut 404 et le corps de réponse json suivant :
```json
{
    "message": "Ressource inexistante",
}
```
### Requête exemple avec échec - code statut 400
Le code statut 400 est retourné si l'id d'organisation demandé est invalide.

```shell
curl --url "http://host/path/organisations/1ag" --request PUT --header "Content-Type: application/json"
     --data "{\"email\" : \"contact@organisation.fr\"}"
```

Fournit une réponse http avec code statut 400 et le corps de réponse json suivant :

```json
{
    "message": "Id de ressource invalide",
}
```
### Requête exemple avec échec - code statut 400
Le code statut 400 est retourné si les données du payload ne sont pas au format attendu : longueur maximum, caractères autorisés, format téléphone, email, url.

```shell
curl --url "http://host/path/organisations/1" --request PUT --header "Content-type: application/json"
     --data {"email" : "organisation.fr"}
```

Fournit une réponse http avec code statut 400 et le corps de réponse json suivant :
```json
{
    "message": "Les données à modifier sont erronées",
    "erreurs" : ["Email invalide"]
}
```