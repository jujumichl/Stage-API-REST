--
-- Structure de la table Specialite
--

CREATE TABLE Specialite (
  ref char(1) NOT NULL,
  sigle char(4) DEFAULT NULL,
  intitule varchar(60) NOT NULL,
  constraint PK_Specialite PRIMARY KEY (ref)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------------------------------
--
-- Structure de la table Etudiant
--

CREATE TABLE Etudiant (
  numero int(8) NOT NULL,
  nom varchar(50) NOT NULL,
  prenom varchar(50) NOT NULL,
  email varchar(100) NOT NULL,
  anneePromo int(4) NOT NULL,
  refSpe char(1),
  constraint PK_Etudiant PRIMARY KEY (numero),
  constraint FK_Etudiant_Specialite FOREIGN KEY (refSpe) REFERENCES Specialite (ref)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table Categorie
--

CREATE TABLE Categorie (
  id int(3) NOT NULL,
  libelle varchar(100) NOT NULL,
  constraint PK_Categorie PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table Dept
--

CREATE TABLE Dept (
  numero int(3) NOT NULL,
  nom varchar(50) NOT NULL,
  constraint PK_Dept PRIMARY KEY (numero)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table Organisation
--

CREATE TABLE Organisation (
  numero int(8) NOT NULL,
  nom varchar(100) NOT NULL,
  idCategorie int(3),
  adresse varchar(100) DEFAULT NULL,
  codePostal varchar(6) DEFAULT NULL,
  ville varchar(100) DEFAULT NULL,
  numeroDept int(3) default NULL,
  tel varchar(50) DEFAULT NULL,
  fax varchar(50) DEFAULT NULL,
  email varchar(100) DEFAULT NULL,
  urlSiteWeb varchar(100) DEFAULT NULL,
  constraint PK_Dept PRIMARY KEY (numero),
  constraint FK_Organisation_Categorie FOREIGN KEY (idCategorie) REFERENCES Categorie (id),
  constraint FK_Organisation_Dept FOREIGN KEY (numeroDept) REFERENCES Dept (numero)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table Contact
--

CREATE TABLE Contact (
  id int(8) NOT NULL,
  numeroOrganisation int(8),
  civilite varchar(50) DEFAULT NULL,
  prenom varchar(50) DEFAULT NULL,
  nom varchar(50) DEFAULT NULL,
  email varchar(100) DEFAULT NULL,
  tel varchar(50) DEFAULT NULL,
  fonction varchar(100) DEFAULT NULL,
  constraint PK_Contact PRIMARY KEY (id),
  constraint FK_Contact_Organisation FOREIGN KEY (numeroOrganisation) REFERENCES Organisation (numero)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table Periodestage
--

CREATE TABLE Periodestage (
  id int(4) NOT NULL,
  dateDeb date DEFAULT NULL,
  dateFin date DEFAULT NULL,
  numAnneeForm int(4) DEFAULT NULL,
  constraint PK_Periodestage PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table Role
--

CREATE TABLE Role (
  id int(2) NOT NULL,
  intitule varchar(50) DEFAULT NULL,
  constraint PK_Role PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table Stage
--

CREATE TABLE Stage (
  id int(8) NOT NULL,
  numeroEtudiant int(8) DEFAULT NULL,
  idPeriodeStage int(4) DEFAULT NULL,
  libelle text DEFAULT NULL,
  theme varchar(255) DEFAULT NULL,
  annee int(4) DEFAULT NULL,
  numeroOrganisation int(8) DEFAULT NULL,
  constraint PK_Stage PRIMARY KEY (id),
  constraint FK_Stage_Organisation FOREIGN KEY (numeroOrganisation) REFERENCES Organisation (numero),
  constraint FK_Stage_Etudiant FOREIGN KEY (numeroEtudiant) REFERENCES Etudiant (numero),
  constraint FK_Stage_PeriodeStage FOREIGN KEY (idPeriodeStage) REFERENCES Periodestage (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table Jouerrole
--

CREATE TABLE Jouerrole (
  idStage int(8) NOT NULL,
  idRole int(2) NOT NULL,
  idContact int(8) DEFAULT NULL,
  constraint PK_Jouerrole PRIMARY KEY (idStage, idRole),
  constraint FK_Jouerrole_Stage FOREIGN KEY (idStage) REFERENCES Stage (id),
  constraint FK_Jouerrole_Role FOREIGN KEY (idRole) REFERENCES Role (id),
  constraint FK_Jouerrole_Contact FOREIGN KEY (idContact) REFERENCES Contact (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
