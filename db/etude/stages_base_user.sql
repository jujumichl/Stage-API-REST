CREATE DATABASE IF NOT EXISTS stages;
-- création compte userStages ayant tous les droits sur les données de la BD

CREATE USER IF NOT EXISTS 'userStages'@'localhost' IDENTIFIED BY 'secret';
GRANT USAGE ON *.* TO 'userStages'@'localhost' ;
GRANT ALL PRIVILEGES ON stages.* TO 'userStages'@'localhost';
