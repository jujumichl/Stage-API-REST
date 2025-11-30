# Documentation API-Rest du domaine des stages
## Introduction
L'API REST api-stages-apirest permet d'appliquer certaines des opérations de base CRUD – Create Retrieve Update Delete – aux 3 types de ressources : organisations, stages et contacts.

Les 4 opérations s'appuient sur les 4 méthodes http POST, GET, PUT et DELETE.

Le format JSON est l'unique format des données renvoyées dans les réponses http. Le champ d'entête Content-Type de la réponse sera toujours valorisé à : `application/json`.

Les éventuelles données passées dans le corps de requête adoptent également le format `application/json`.

## Codes Status
Les codes statut http retournés par les différentes requêtes peuvent être parmi les codes suivants :
|Code statut |	Libellé                                                                     |
|------------|------------------------------------------------------------------------------|
| 200	     | OK ou mention de l'action qui a réussi                                       |
| 201	     | La ressource a bien été créée                                                |
| 209	     | La ressource est en conflit                                                  |
| 400	     | Requête non comprise ou avec paramètres manquants, invalides ou inexistants  |
| 403	     | Interdiction d’accès à la ressource                                          |
| 404	     | Ressource non trouvée                                                        |
| 503	     | Base de données inaccessible                                                 |

Les URIs fournies dans la documentation correspondent au segment d'URL à concaténer derrière la base de l'URL qui dépend de l'hôte et du chemin d'accès où est hébergée l'API.

Ainsi, si l'API est hébergée sous `http://SLAM02-00/SIO2/API-REST-Stages/public`, l'URL à saisir pour obtenir la liste des organisations sera la suivante :

| `http://SLAM02-00/SIO2/API-REST-Stages/public` | /  |   index.php	           |  / |	  organisations       |
|----------------------------------------------|----|--------------------------|----|-------------------------|
|          Base de l'URL					   |    |   contrôleur principal   |    |     nom de ressource    |

## Ressource [Organisations](./api-organisations.md)

## Ressource [Stages](./api-stages.md)

## Ressource [Contacts](./api-contacts.md)