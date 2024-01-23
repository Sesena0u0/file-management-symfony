DROP DATABASE IF EXISTS e_dt;

CREATE DATABASE IF NOT EXISTS e_dt;
USE e_dt;
# -----------------------------------------------------------------------------
#       TABLE : SCEANCE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS SCEANCE
 (
   ID_SCEANCE INTEGER NOT NULL AUTO_INCREMENT ,
   ID_PROFESSEURS INTEGER NOT NULL  ,
   ID_MATIERES INTEGER NOT NULL  ,
   ID_CLASSES INTEGER NOT NULL  ,
   ID_SALLE INTEGER NOT NULL  ,
   DATE DATE NOT NULL  ,
   DEBUT_HEURE TIME NOT NULL  ,
   FIN_HEURE TIME NOT NULL  
   , PRIMARY KEY (ID_SCEANCE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : MATIERES
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS MATIERES
 (
   ID_MATIERES INTEGER NOT NULL AUTO_INCREMENT ,
   ID_PROFESSEURS INTEGER NULL  ,
   NOM CHAR (32) NOT NULL  ,
   DUREE INTEGER NOT NULL  
   , PRIMARY KEY (ID_MATIERES) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : SALLE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS SALLE
 (
   ID_SALLE INTEGER NOT NULL AUTO_INCREMENT ,
   NOM CHAR (32) NOT NULL  ,
   CAPACITE_SALLE INTEGER NOT NULL  
   , PRIMARY KEY (ID_SALLE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : PROFESSEURS
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS PROFESSEURS
 (
   ID_PROFESSEURS INTEGER NOT NULL AUTO_INCREMENT ,
   NOM CHAR (32) NOT NULL  ,
   PRENOM CHAR (32) NOT NULL  
   , PRIMARY KEY (ID_PROFESSEURS) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : CLASSES
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CLASSES
 (
   ID_CLASSES INTEGER NOT NULL AUTO_INCREMENT ,
   NOM CHAR (32) NOT NULL  ,
   NBR_ETUDIANTS INTEGER NULL  
   , PRIMARY KEY (ID_CLASSES) 
 ) 
 comment = "";


# -----------------------------------------------------------------------------
#       CREATION DES REFERENCES DE TABLE
# -----------------------------------------------------------------------------


ALTER TABLE SCEANCE 
  ADD FOREIGN KEY FK_SCEANCE_PROFESSEURS (ID_PROFESSEURS)
      REFERENCES PROFESSEURS (ID_PROFESSEURS) ON DELETE CASCADE ON UPDATE CASCADE ;


ALTER TABLE SCEANCE 
  ADD FOREIGN KEY FK_SCEANCE_MATIERES (ID_MATIERES)
      REFERENCES MATIERES (ID_MATIERES) ON DELETE CASCADE ON UPDATE CASCADE ;


ALTER TABLE SCEANCE 
  ADD FOREIGN KEY FK_SCEANCE_CLASSES (ID_CLASSES)
      REFERENCES CLASSES (ID_CLASSES) ON DELETE CASCADE ON UPDATE CASCADE ;


ALTER TABLE SCEANCE 
  ADD FOREIGN KEY FK_SCEANCE_SALLE (ID_SALLE)
      REFERENCES SALLE (ID_SALLE) ON DELETE CASCADE ON UPDATE CASCADE ;


ALTER TABLE MATIERES 
  ADD FOREIGN KEY FK_MATIERES_PROFESSEURS (ID_PROFESSEURS)
      REFERENCES PROFESSEURS (ID_PROFESSEURS) ON DELETE CASCADE ON UPDATE CASCADE ;

