USE tp_sio2_bdjourneeintegration;

#------------------------------------------------------------
# Table: Developpeur
#------------------------------------------------------------

DROP TABLE IF EXISTS `Developpeur`;

CREATE TABLE Developpeur (
    id int Auto_increment NOT NULL,
    nom Varchar(20) NOT NULL,
    prenom Varchar(15) NOT NULL,
    CONSTRAINT Classe_PK PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb3 COLLATE = utf8mb3_general_ci;

INSERT INTO
    Developpeur
VALUES ('1', 'COVER', 'Harry'),
    ('2', 'TOUIL', 'Sacha'),
    ('3', 'GUETTE', 'Garry');

#------------------------------------------------------------
# Table: Competence
#------------------------------------------------------------
CREATE TABLE Competence(
        id      Int  Auto_increment  PRIMARY KEY NOT NULL ,
        nom Varchar (40) NOT NULL
)ENGINE=InnoDB;

#------------------------------------------------------------
# Données de départ
#------------------------------------------------------------
INSERT INTO Competence VALUES ('1', 'Développement Web- CSS');
INSERT INTO Competence VALUES ('2', 'Développement Web- CSS');
INSERT INTO Competence VALUES ('4', 'Développement Web- CSS');
INSERT INTO Competence VALUES ('5', 'Développement Web- CSS');
INSERT INTO Competence VALUES ('6', 'Développement Web- CSS');



#------------------------------------------------------------
# Habilitations
#------------------------------------------------------------
GRANT SELECT ON `tp_sio2_bdjourneeintegration`.`Developpeur` TO 'JI_Dev_Read'@'%';
GRANT SELECT, DELETE ON `tp_sio2_bdjourneeintegration`.`Developpeur` TO 'toto'@'%';
GRANT INSERT ,SELECT ON `tp_sio2_bdjourneeintegration`.`Developpeur` TO 'lala'@'%';
GRANT UPDATE,SELECT ON `tp_sio2_bdjourneeintegration`.`Developpeur` TO 'lili'@'%';

GRANT SELECT ON `tp_sio2_bdjourneeintegration`.`Competence` TO 'CompRead'@'%';
GRANT SELECT, DELETE ON `tp_sio2_bdjourneeintegration`.`Competence` TO 'tata'@'%';
GRANT SELECT, UPDATE ON `tp_sio2_bdjourneeintegration`.`Competence` TO 'CompModif'@'%';

