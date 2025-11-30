CREATE DATABASE IF NOT EXISTS stages;
-- création compte userStages ayant tous les droits sur les données de la BD

CREATE USER 'userStages'@'localhost' IDENTIFIED BY 'secret';
GRANT USAGE ON *.* TO 'userStages'@'localhost' ;
GRANT SELECT , INSERT , UPDATE , DELETE ON stages.* TO 'userStages'@'localhost';
