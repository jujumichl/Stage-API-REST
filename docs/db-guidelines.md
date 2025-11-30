# Bonnes pratiques sur la structure d'une base de données

## Processus d'élaboration
Le schéma de données sera élaboré via un diagramme de classes sur lequel figurera les classes, leurs propriétés typées et les associations avec multiplicité et navigabilité.
Par défaut, la navigabilité des associations sera unidirectionnelle. Elle pourra être bidirectionnelle après justification en fonction des besoins.

Le diagramme de classes sera ensuite traduit en entités sous l'ORM Doctrine.

## Conventions pour le nommage des classes et propriétés :
1. Il n’y a pas de blanc ni de caractère accentué dans les noms d'entités.
2. Chaque nom d'entité commence par une majuscule et est suivi de minuscules. S’il est
composé de plusieurs mots, ceux‐ci sont collés et distingués par une majuscule.
3. Chaque nom de propriété est écrit en minuscule. S’il est composé de plusieurs mots, ils sont
collés et distingués par une majuscule. 
4. Chaque nom d'entité et de propriété correspond bien à son contenu.
5. On privilégiera « id » comme nom de propriété identifiant d’une entité.

## Conventions pour le typage des propriétés
1. Excepté cas à justifier, l'identifiant d'une entité sera de type entier et auto-incrémenté.

## Autres vérifications à réaliser
1. Vérifier l'absence de redondance de données, l'utilité réelle de toutes les données.
2. Vérifier l'absence de données polysèmes. Si besoin, les conserver après justification. Sinon les renommer.

## Sous l'ORM Doctrine
1. Excepté pour l'identifiant, la contrainte NULL sera appliquée à chaque propriété.
2. Les contraintes de validation des données seront inscrites au niveau des propriétés des entités.