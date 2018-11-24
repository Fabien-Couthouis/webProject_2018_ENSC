CREATE TABLE utilisateur
(
    id varchar(30) PRIMARY KEY,
    mdp varchar(30),
    mail varchar(100)
);

CREATE TABLE sus
(
    id integer PRIMARY KEY,
    question varchar(100)
);


CREATE TABLE nasa
(
    id integer PRIMARY KEY,
    indic varchar (50),
    question varchar(300)
);

CREATE TABLE campagne
(
    id integer PRIMARY KEY,
    nom varchar(30),
    descr varchar(300),
    dateCreation date,
    etat boolean
);

CREATE TABLE experience
(
    id integer PRIMARY KEY,
    nom varchar(30),
    descr varchar(300),
    dateCreation date,
    typeExp varchar(30),
    idCamp integer,
    FOREIGN KEY (idCamp) REFERENCES campagne (id)
);

CREATE TABLE reponse
(
    id integer PRIMARY KEY,
    idExp integer,
    typeTest varchar(30),
    score integer,
    idUtilisateur varchar(30),
    FOREIGN KEY (idExp) REFERENCES experience (id),
    FOREIGN KEY (idUtilisateur) REFERENCES utilisateur (id)
);

CREATE TABLE gerer
(
    id integer PRIMARY KEY,
    idCamp integer,
    idUtilisateur varchar(30),
    FOREIGN KEY (idCamp) REFERENCES campagne (id),
    FOREIGN KEY (idUtilisateur) REFERENCES utilisateur (id)
);