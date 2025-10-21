-- script d'initialisation de la base de données pour l'application web avec les droits de l'utilisateur
DROP DATABASE IF EXISTS `tp_sio2_bdjourneeintegration`;

CREATE DATABASE IF NOT EXISTS `tp_sio2_bdjourneeintegration` CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE DATABASE IF NOT EXISTS `tp_sio2_bdjourneeintegration` CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP USER IF EXISTS 'JI_Dev_Read'@'%';
DROP USER IF EXISTS 'toto'@'%';
DROP USER IF EXISTS 'CompRead'@'%';
DROP USER IF EXISTS 'tata'@'%';
DROP USER IF EXISTS 'lala'@'%';
DROP USER IF EXISTS 'lili'@'%';

CREATE USER 'JI_Dev_Read'@'%' IDENTIFIED BY 'pwdJIPourDev_R';
CREATE USER 'toto'@'%' IDENTIFIED BY 'Mdp_toto';
CREATE USER 'CompRead'@'%' IDENTIFIED BY 'Mdp_CompRead';
CREATE USER 'tata'@'%' IDENTIFIED BY 'Mdp_tata';
CREATE USER 'lili'@'%' IDENTIFIED BY 'Mdp_lili';
CREATE USER 'lala'@'%' IDENTIFIED BY 'Mdp_lala';
CREATE USER 'CompModif'@'%' IDENTIFIED BY 'Mdp_CompModif';

USE `tp_sio2_bdjourneeintegration`;

