## Obtenir tous les contacts
### URI
```plaintext
GET /contacts
```

### Paramètres d'URL
Aucun

### Réponse
En cas de succès, retourne [`<200>`](./api.md#codes-status) et la réponse suivante :

| Propriété                | Type           | Description                   |
|--------------------------|----------------|-------------------------------|
| `message`                | string         | Ok                            |
| `data`                   | Object array   | Tableau d'objets contact      |

Les contacts seront triés de manière croissante sur le nom.

Chaque objet contact présente les propriétés ci-après.
Un objet organisation est visible [ici](./api-organisations.md#un-objet-organisation).
```json
{
    "id": entier,
    "organisation" : object organisation,
    "nom": string,
    "prenom": string,
    "email": string,
    "tel": string,
    "fonction": string,
}
```

### Requête exemple avec succès - code statut 200

```shell
curl --url "http://host/path/contacts"
```

Fournit une réponse http avec code statut 200 et le corps de réponse json suivant :
```json
{
  "message": "OK",
  "data": [
      {
         "id": 5,
         "organisation" : 
            { "id": 1,
              "nom": "Rectorat de Rennes",
              "rue": "...",

              "urlSiteWeb": "...",
              "orgaAssurance": "...",
              "numAssurance": "..."
            },
         "nom": "Boullin",
         "prenom": "Arnaud",
         "email": "arnaud.boullin@ac-rennes.fr",
         "tel": null,
         "fonction": "Leader technique"
      },
      {
         "id": 15,
         "organisation" : 
            { "id": 51,
              "nom": "Groupe Avril",
              "rue": "...",

              "urlSiteWeb": "...",
              "orgaAssurance": "...",
              "numAssurance": "..."
            },
         "nom": "Farminot",
         "prenom": "Samia",
         "email": "samia.farminot@groupeavril.com",
         "tel": null,
         "fonction": "Responsable informatique"
      },
    ...
    ] 
}
```

## Obtenir un contact spécifié par son id

### URI
```plaintext
GET /contacts/:idContact
```

### Paramètres
| Propriété                | Type           | Description                   |
|--------------------------|----------------|-------------------------------|
| :idContact               | integer        | id du contact                 |

### Réponse
En cas de succès, retourne [`<200>`](./api.md#codes-status) et la réponse suivante :

| Propriété                | Type           | Description                   |
|--------------------------|----------------|-------------------------------|
| `message`                | string         | Ok                            |
| `data`                   | Object         | Objet contact                 |

Un objet contact présente les propriétés ci-après.
Un objet organisation est visible [ici](./api-organisations.md#un-objet-organisation).
```json
{
    "id": entier,
    "organisation" : object organisation,
    "nom": string,
    "prenom": string,
    "email": string,
    "tel": string,
    "fonction": string
}
```
### Requête exemple avec succès - code statut 200
```shell
curl --url "http://host/path/contacts/15"
```

Fournit une réponse http avec code statut 200 et le corps de réponse json suivant :
```json
{
    "message": "OK",
    "data": {
         "id": 15,
         "organisation" : 
            { "id": 51,
              "nom": "Groupe Avril",
              "rue": "...",

              "urlSiteWeb": "...",
              "orgaAssurance": "...",
              "numAssurance": "..."
            },
         "nom": "Farminot",
         "prenom": "Samia",
         "email": "samia.farminot@groupeavril.com",
         "tel": null,
         "fonction": "Responsable informatique"
    }
}
```
### Requête exemple avec échec - code statut 404 - Id contact inexistant
Le code statut 404 est retourné si l'id de contact est inexistant.

```shell
curl --url "http://host/path/contacts/899"
```

Fournit une réponse http avec code statut 404 et le corps de réponse json suivant :
```json
{
    "message": "Ressource inexistante",
}
```
### Requête exemple avec échec - code statut 400 - Id contact invalide
Le code statut 400 est retourné si l'id de contact demandé est invalide.

```shell
curl --url "http://host/path/contacts/1fg"
```

Fournit une réponse http avec code statut 400 et le corps de réponse json suivant :
```json
{
    "message": "Id de ressource invalide",
}
```
## Créer un contact
### URI
```plaintext
POST /contacts
```

### Paramètres d'URL
Aucun

### Données du payload - passées dans le corps de la requête au format JSON
| Propriété         | Type          | Description                            |
|-------------------|---------------|----------------------------------------|
| civilite          | string        | civilité du contact parmi M ou Mme     |
| nom               | string        | son nom                                |
| prenom            | string        | son prenom                             |
| email             | string        | son email                              |
| idOrganisation    | integer       | l'id de son organisation               |
| tel               | string        | son téléphone                          |
| fonction          | string        | sa fonction dans l'organisation        |

Toutes les données citées ci-dessus excepté tel et fonction doivent être présentes dans le payload.

### Réponse
En cas de succès, retourne [`<200>`](./api.md#codes-status) et la réponse suivante :

| Propriété                | Type           | Description                                           |
|--------------------------|----------------|-------------------------------------------------------|
| `message`                | string         | Contact d'id x créé                                   |
| `data`                   | Object data    | Fournit des données sur la nouvelle ressource         |

L'objet data comporte les propriétés suivantes :

| Propriété                | Type           | Description                                         |
|--------------------------|----------------|-----------------------------------------------------|
| `_selfLink`              | string         | Lien absolu vers la nouvelle ressource              |

La propriété `_selfLink` donne un lien vers le détail du contact.

### Requête exemple avec succès - code statut 200 - données obligatoires et facultatives présentes
```shell
curl --url "http://host/path/contacts" --request POST --header "Content-type: application/json"
     --data "{\"civilite\": \"Mme\",
              \"nom\": \"Courtil\",
              \"prenom\": \"Martine\",
              \"email\": \"martine.courtil@groupeavril.com\",
              \"tel\": \"0299142536\",
              \"fonction\": \"Responsable d’applications\",
              \"idOrganisation\": 15
            }"
```

Fournit une réponse http avec code statut 200 et le corps de réponse json suivant :
```json
{
    "message": "Contact d'id 100 créé"
    "data": { "_selfLink" : "http://host/path/contacts/100" }
}
```
### Requête exemple avec succès - code statut 200 - données obligatoires présentes seulement
```shell
curl --url "http://host/path/contacts" --request POST --header "Content-type: application/json"
     --data "{\"civilite\": \"M\",
              \"nom\": \"Fournier\",
              \"prenom\": \"Ewen\",
              \"email\": \"ewen.fournier@groupeavril.com\",
              \"idOrganisation\": 15
            }"
```

Fournit une réponse http avec code statut 200 et le corps de réponse json suivant :
```json
{
    "message": "Contact d'id 110 créé"
    "data": { "_selfLink" : "http://host/path/contacts/110" }
}
```
### Requête exemple avec échec - code statut 400 - données obligatoires non renseignées
Le code statut 400 est retourné si les données du payload ne sont pas au format attendu.

```shell
curl --url "http://host/path/contacts" --request PUT --header "Content-type: application/json"
     --data "{\"civilite\": \"M\",
              \"email\": \"simone.gravier@groupeavril.com\",
              \"tel\": \"0299112233\"
            }"
```

Fournit une réponse http avec code statut 400 et le corps de réponse json suivant :
```json
{
    "message": "Les données fournies sont erronées",
    "erreurs" : ["Nom non renseigné", "Prénom non renseigné", "Id organisation non renseigné"]
}
```
### Requête exemple avec échec - code statut 400 - email non valide
Le code statut 400 est retourné si les données du payload ne sont pas au format attendu.

```shell
curl --url "http://host/path/contacts" --request PUT --header "Content-type: application/json"
     --data "{\"civilite\": \"M\",
              \"nom\": \"Brun\",
              \"prenom\": \"Serge\",
              \"email\": \"serge.brun\"
            }"
```

Fournit une réponse http avec code statut 400 et le corps de réponse json suivant :
```json
{
    "message": "Les données à modifier sont erronées",
    "erreurs" : ["Email invalide"]
}
```