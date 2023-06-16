DROP DATABASE IF EXISTS sql8516965;
CREATE DATABASE sql8516965;
USE sql8516965;

CREATE TABLE UTENTE(
		Username 	 varchar(30) primary key,
        Password 	 varchar(30) not null,
		Email 		 varchar(50) not null
) ENGINE = INNODB;

CREATE TABLE LISTA_ANIMALI(
		UsernameUtente 	 varchar(30),
        Animale 	 	 varchar(30), 
        primary key(UsernameUtente, Animale),
        CONSTRAINT  FOREIGN KEY (UsernameUtente) REFERENCES UTENTE(Username) ON DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE PUNTEGGIO(
		UsernameUtente 	 varchar(30) primary key, #references UTENTE(Username) ON DELETE CASCADE,
        GiocoMemory 	 integer,
		GiocoPesi 		 integer,
        GiocoQuiz 		 integer, 
        CONSTRAINT  FOREIGN KEY (UsernameUtente) REFERENCES UTENTE(Username) ON DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE MESSAGGIO(
		Id 	integer primary key AUTO_INCREMENT,
        Messaggio BLOB,
        Immagine BLOB
) ENGINE = INNODB;

######################################## E-COMMERCE ########################################
CREATE TABLE PRODOTTO (
		Nome varchar(100) PRIMARY KEY,
        Prezzo decimal(8,2) not null,
        Quantita mediumint not null,
        Descrizione text,
        Img varchar(500) not null DEFAULT "",
        Sezione enum('Cibo','ProdottiSanitari','Accessoristica','Cuccioli') NOT NULL
) ENGINE = INNODB;

CREATE TABLE CATEGORIA (
		Nome varchar(100) PRIMARY KEY
) ENGINE = INNODB;

CREATE TABLE ASSOCIAZIONE_PROD_CAT (
		NomeProdotto varchar(100),
        NomeCategoria varchar(100),
        PRIMARY KEY(NomeProdotto,NomeCategoria),
        FOREIGN KEY (NomeProdotto) references PRODOTTO(Nome) ON UPDATE CASCADE ON DELETE CASCADE,
        FOREIGN KEY (NomeCategoria) references CATEGORIA(Nome) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE CARRELLO(
		UsernameUtente 	 varchar(30),
        NomeProdotto 	 varchar(100),
		Quantità 		 integer, 
        PRIMARY KEY(UsernameUtente,NomeProdotto),
        FOREIGN KEY (UsernameUtente) references UTENTE(Username) ON DELETE CASCADE,
		FOREIGN KEY (NomeProdotto) references PRODOTTO(Nome) ON DELETE CASCADE
) ENGINE = INNODB;

############################################ INSERT INTO ######################################
INSERT INTO UTENTE (Username, Password, Email)
values ('Lambe', '123', 'lambe@gamil.com');
INSERT INTO LISTA_ANIMALI (UsernameUtente, Animale)
values ('Lambe', 'Toro');
INSERT INTO PUNTEGGIO (UsernameUtente, GiocoMemory, GiocoPesi, GiocoQuiz)
values ('Lambe', 20, 2, 1);

INSERT INTO UTENTE (Username, Password, Email)
values ('Kev', '123', 'kev@gmail.com');
INSERT INTO LISTA_ANIMALI (UsernameUtente, Animale)
values ('Kev', 'Lucertola');
INSERT INTO PUNTEGGIO (UsernameUtente, GiocoMemory, GiocoPesi, GiocoQuiz)
values ('Kev', 15, 6, 1);

INSERT INTO UTENTE (Username, Password, Email)
values ('Andre', '123', 'andre@gmail.com');
INSERT INTO LISTA_ANIMALI (UsernameUtente, Animale)
values ('Andre', 'Coniglio');
INSERT INTO PUNTEGGIO (UsernameUtente, GiocoMemory, GiocoPesi, GiocoQuiz)
values ('Andre', 13, 10, 4);

INSERT INTO UTENTE (Username, Password, Email)
values ('Buz', '123', 'buz@gmaio.com');
INSERT INTO LISTA_ANIMALI (UsernameUtente, Animale)
values ('Buz', 'T-rex');
INSERT INTO PUNTEGGIO (UsernameUtente, GiocoMemory, GiocoPesi, GiocoQuiz)
values ('Buz', 9, 12, 2);

INSERT INTO UTENTE (Username, Password, Email)
values ('Woody', '123', 'woody@gmail.com');
INSERT INTO LISTA_ANIMALI (UsernameUtente, Animale)
values ('Woody', 'Cavallo');
INSERT INTO PUNTEGGIO (UsernameUtente, GiocoMemory, GiocoPesi, GiocoQuiz)
values ('Woody', 10, 4, 5);

INSERT INTO UTENTE (Username, Password, Email)
values ('Elena', '123', 'elena@gmail.com');
INSERT INTO LISTA_ANIMALI (UsernameUtente, Animale)
values ('Elena', 'Gatto');
INSERT INTO PUNTEGGIO (UsernameUtente, GiocoMemory, GiocoPesi, GiocoQuiz)
values ('Elena', 13, 13, 8);

INSERT INTO UTENTE (Username, Password, Email)
values ('Sissi', '123', 'sissi@gmail.com');
INSERT INTO LISTA_ANIMALI (UsernameUtente, Animale)
values ('Sissi', 'Rospo');
INSERT INTO PUNTEGGIO (UsernameUtente, GiocoMemory, GiocoPesi, GiocoQuiz)
values ('Sissi', 18, 1, 2);

INSERT INTO UTENTE (Username, Password, Email)
values ('Zalia', '123', 'zalia@gamil.com');
INSERT INTO LISTA_ANIMALI (UsernameUtente, Animale)
values ('Zalia', 'Ramarro');
INSERT INTO PUNTEGGIO (UsernameUtente, GiocoMemory, GiocoPesi, GiocoQuiz)
values ('Zalia', 22, 10, 13);

INSERT INTO UTENTE (Username, Password, Email)
values ('Dante', '123', 'dante@gamil.com');
INSERT INTO LISTA_ANIMALI (UsernameUtente, Animale)
values ('Dante', 'Cane');
INSERT INTO PUNTEGGIO (UsernameUtente, GiocoMemory, GiocoPesi, GiocoQuiz)
values ('Dante', 16, 25, 5);

INSERT INTO UTENTE (Username, Password, Email)
values ('Zic', '123', 'zic@gmail.com');
INSERT INTO LISTA_ANIMALI (UsernameUtente, Animale)
values ('Zic', 'Topo');
INSERT INTO PUNTEGGIO (UsernameUtente, GiocoMemory, GiocoPesi, GiocoQuiz)
values ('Zic', 14, 6, 4);

INSERT INTO UTENTE (Username, Password, Email)
values ('Topolino', '123', 'topolino@gmail.com');
INSERT INTO LISTA_ANIMALI (UsernameUtente, Animale)
values ('Topolino', 'Topo');
INSERT INTO PUNTEGGIO (UsernameUtente, GiocoMemory, GiocoPesi, GiocoQuiz)
values ('Topolino', 11, 6, 4);

INSERT INTO UTENTE (Username, Password, Email)
values ('admin', 'admin', 'admin@gmail.com');
INSERT INTO LISTA_ANIMALI (UsernameUtente, Animale)
values ('admin', 'cavallo');
INSERT INTO PUNTEGGIO (UsernameUtente)
values ('admin');





INSERT INTO MESSAGGIO (Messaggio)
values ('Primo messaggio');
INSERT INTO MESSAGGIO (Messaggio, Immagine)
values ('Secondo messaggio', 'animal2.jpg');

-- P R O D O T T O INSERT INTO
INSERT INTO PRODOTTO (Nome,Prezzo,Quantita,Descrizione,Img,Sezione)
VALUES ("Distributore d'acqua piccolo per animali",16.99,3,"Distribuore di acqua piccolo per tutti gli animali domestici. Pratico e funzionale.",
"images/shop/distributoreacqua.jpg",'Accessoristica');

INSERT INTO PRODOTTO (Nome,Prezzo,Quantita,Descrizione,Img,Sezione)
VALUES ("Ciotola in acciaio",7.99,2,"Ciotola per contenere l'acqua o cibo in acciaio.Robusta e resistente agli urti.",
"images/shop/ciotola1.jpg",'Accessoristica');

INSERT INTO PRODOTTO (Nome,Prezzo,Quantita,Descrizione,Img,Sezione)
VALUES ("Doppia Ciotola",15.99,1,"Ciotola con due scomparti per contenere acqua e cibo.Utile e pratica.",
"images/shop/ciotola2.jpg",'Accessoristica');

INSERT INTO PRODOTTO (Nome,Prezzo,Quantita,Descrizione,Img,Sezione)
VALUES ("Ciotola per gatti",15.99,1,"Ciotola utilizzabile per dare cibo o acqua al proprio gatto.Compatta,elegante e resistente.",
"images/shop/ciotola3.jpg",'Accessoristica');

INSERT INTO PRODOTTO (Nome,Prezzo,Quantita,Descrizione,Img,Sezione)
VALUES ("Ciotola per acqua",15.99,1,"Ciotola utilizzabile per dare cibo o acqua al proprio animale domestico.Robusta e utilissima.",
"images/shop/ciotola4.jpg",'Accessoristica');

INSERT INTO PRODOTTO (Nome,Prezzo,Quantita,Descrizione,Img,Sezione)
VALUES ("Ciotola per Cani",17.99,1,"Ciotola con doppio scomparto: uno per acqua e uno per cibo. Pratica per cani di media/piccola taglia.",
"images/shop/ciotola5.jpg",'Accessoristica');

-- C A T E G O R I A INSERT INTO
INSERT INTO CATEGORIA (Nome) VALUES ('Animali domestici');
INSERT INTO CATEGORIA (Nome) VALUES ('Cani');
INSERT INTO CATEGORIA (Nome) VALUES ('Gatti');
INSERT INTO CATEGORIA (Nome) VALUES ('Uso esterno');
INSERT INTO CATEGORIA (Nome) VALUES ('Uso interno');
INSERT INTO CATEGORIA (Nome) VALUES ('Svago');

-- A S S O C I A Z I O N E _ P R O D _ C A T

# stored procedures
#######################################################################################################################
INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Distributore d'acqua piccolo per animali","Animali domestici");
INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Distributore d'acqua piccolo per animali","Uso interno");
INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Distributore d'acqua piccolo per animali","Uso esterno");

INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Ciotola in acciaio","Animali domestici");
INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Ciotola in acciaio","Uso interno");
INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Ciotola in acciaio","Uso esterno");

INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Doppia Ciotola","Animali domestici");
INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Doppia Ciotola","Uso interno");
INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Doppia Ciotola","Uso esterno");

INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Ciotola per gatti","Gatti");
INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Ciotola per gatti","Uso interno");
INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Ciotola per gatti","Uso esterno");

INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Ciotola per acqua","Animali domestici");
INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Ciotola per acqua","Uso interno");
INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Ciotola per acqua","Uso esterno");

INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Ciotola per Cani","Uso esterno");
INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Ciotola per Cani","Uso interno");
INSERT INTO ASSOCIAZIONE_PROD_CAT(NomeProdotto,NomeCategoria) VALUES ("Ciotola per Cani","Cani");

# store procedure --> inserisci utente
start transaction;
 delimiter |
 CREATE PROCEDURE CreaUtente(Username varchar(30), Password varchar(30), Email varchar(50))
	BEGIN
    INSERT INTO UTENTE SET  Username = Username, Password = Password, Email = Email;
    COMMIT;
    END
| delimiter ;

# store procedure --> elimina utente
start transaction;
 delimiter |
 CREATE PROCEDURE EliminaUtente(UsernameUtente varchar(30))
	BEGIN
    #SET SQL_SAFE_UPDATES = 0;
    DELETE FROM UTENTE WHERE UsernameUtente = Username;
    #SET SQL_SAFE_UPDATES = 1;
    COMMIT;
    END
| delimiter ;

# store procedure --> elimina utente dai punteggi
start transaction;
 delimiter |
 CREATE PROCEDURE EliminaUtenteDaPunteggi(UsernameEliminare varchar(30))
	BEGIN
    #SET SQL_SAFE_UPDATES = 0;
    DELETE FROM PUNTEGGIO WHERE UsernameEliminare = UsernameUtente;
    #SET SQL_SAFE_UPDATES = 1;
    COMMIT;
    END
| delimiter ;

# store procedure --> elimina utente dalla lista animali preferiti
start transaction;
 delimiter |
 CREATE PROCEDURE EliminaUtenteDaAnimali(UsernameEliminare varchar(30))
	BEGIN
    #SET SQL_SAFE_UPDATES = 0;
    DELETE FROM LISTA_ANIMALI WHERE UsernameEliminare = UsernameUtente;
    #SET SQL_SAFE_UPDATES = 1;
    COMMIT;
    END
| delimiter ;

# store procedure --> modifica password
start transaction;
 delimiter |
 CREATE PROCEDURE ModificaPw(Username varchar(30), Password varchar(30))
	BEGIN
    UPDATE UTENTE  SET Password = Password WHERE (UTENTE.Username = Username);
    COMMIT;
    END
| delimiter ;

# store procedure --> inserisci in lista animali preferiti
start transaction;
 delimiter |
 CREATE PROCEDURE InserisciListaAnimali(Username varchar(30), Animale varchar(30))
	BEGIN
    INSERT INTO LISTA_ANIMALI SET  UsernameUtente = Username, Animale = Animale;
    COMMIT;
    END
| delimiter ;

# store procedure --> inserisci utente nella lista dei punteggi
start transaction;
 delimiter |
 CREATE PROCEDURE InserisciInPunteggio(Username varchar(30))
	BEGIN
    INSERT INTO PUNTEGGIO SET  UsernameUtente = Username;
    COMMIT;
    END
| delimiter ;

# store procedure --> modifica punteggio memory
start transaction;
 delimiter |
 CREATE PROCEDURE ModificaMemory(Username varchar(30), punteggio integer)
	BEGIN
    UPDATE PUNTEGGIO  SET GiocoMemory = punteggio WHERE (UsernameUtente = Username);
    COMMIT;
    END
| delimiter ;

# store procedure --> modifica punteggio pesi
start transaction;
 delimiter |
 CREATE PROCEDURE ModificaPesi(Username varchar(30), punteggio integer)
	BEGIN
    UPDATE PUNTEGGIO  SET GiocoPesi = punteggio WHERE (UsernameUtente = Username);
    COMMIT;
    END
| delimiter ;

# store procedure --> modifica punteggio quiz
start transaction;
 delimiter |
 CREATE PROCEDURE ModificaQuiz(Username varchar(30), punteggio integer)
	BEGIN
    UPDATE PUNTEGGIO  SET GiocoQuiz = punteggio WHERE (UsernameUtente = Username);
    COMMIT;
    END
| delimiter ;
###################################################### E C O M M E R C E #####################################################
# store procedure --> Inserisci Prodotto
start transaction;
 delimiter |
 CREATE PROCEDURE InserisciProdotto(NomeP varchar(100),PrezzoP decimal(8,2),QuantitaP mediumint(9),
 DescrizioneP text, ImgP varchar(500), SezioneP enum('Cibo','ProdottiSanitari','Accessoristica','Cuccioli'))
	BEGIN
    INSERT INTO Prodotto SET
				Nome=NomeP,Prezzo=PrezzoP,Quantita=QuantitaP,Descrizione=DescrizioneP,Img=ImgP,Sezione=SezioneP;
    COMMIT;
    END
| delimiter ;

# store procedure --> Inserisci Categoria
start transaction;
 delimiter |
 CREATE PROCEDURE InserisciCategoria(NomeC varchar(100))
	BEGIN
    INSERT INTO Categoria SET
				Nome=NomeC;
    COMMIT;
    END
| delimiter ;

# store procedure --> Inserisci Associazione_Prodotto_Categoria
start transaction;
 delimiter |
 CREATE PROCEDURE InserisciAssociazione_prod_cat(NomeP varchar(100),NomeC varchar(100))
	BEGIN
    INSERT INTO ASSOCIAZIONE_PROD_CAT SET NomeProdotto=NomeP,NomeCategoria=NomeC;
    COMMIT;
    END
| delimiter ;

# store procedure --> elimina Prodotto
start transaction;
 delimiter |
 CREATE PROCEDURE EliminaProdotto(NomeProdotto varchar(100))
	BEGIN
    DELETE FROM Prodotto WHERE  Nome=NomeProdotto;
    COMMIT;
    END
| delimiter ;

# store procedure --> elimina Categoria
start transaction;
 delimiter |
 CREATE PROCEDURE EliminaCategoria(NomeCategoria varchar(100))
	BEGIN
    DELETE FROM Categoria WHERE  Nome=NomeCategoria;
    COMMIT;
    END
| delimiter ;

# store procedure --> elimina Associazione Prodotto Categoria
start transaction;
 delimiter |
 CREATE PROCEDURE EliminaAssociazioneProdCat(NomeProd varchar(100),NomeCat varchar(100))
	BEGIN
    DELETE FROM ASSOCIAZIONE_PROD_CAT WHERE  NomeCategoria=NomeCat AND NomeProdotto=NomeProd;
    COMMIT;
    END
| delimiter ;

# store procedure --> modifica Prezzo Prodotto
start transaction;
 delimiter |
 CREATE PROCEDURE ModificaPrezzoProdotto(NomeP varchar(100),PrezzoP decimal(8,2))
	BEGIN
    if((SELECT count(Nome) FROM Prodotto WHERE Nome=NomeP)>0 ) THEN
		UPDATE Prodotto SET Prezzo=PrezzoP WHERE Nome=NomeP;
        COMMIT;
    END IF;
    END
| delimiter ;

# store procedure --> modifica Descrizione Prodotto
start transaction;
 delimiter |
 CREATE PROCEDURE ModificaDescrizioneProdotto(NomeP varchar(100),DescrizioneP text)
	BEGIN
    if((SELECT count(Nome) FROM Prodotto WHERE Nome=NomeP)>0 ) THEN
		UPDATE Prodotto SET Descrizione=DescrizioneP WHERE Nome=NomeP;
        COMMIT;
    END IF;
    END
| delimiter ;

# store procedure --> modifica Quantita Prodotto
start transaction;
 delimiter |
 CREATE PROCEDURE ModificaQuantitaProdotto(NomeP varchar(100),QuantitaP mediumint(9))
	BEGIN
    if((SELECT count(Nome) FROM Prodotto WHERE Nome=NomeP)>0 ) THEN
		UPDATE Prodotto SET Quantita=QuantitaP WHERE Nome=NomeP;
        COMMIT;
    END IF;
    END
| delimiter ;

# store procedure --> modifica Immagine Prodotto
start transaction;
 delimiter |
 CREATE PROCEDURE ModificaImgProdotto(NomeP varchar(100),ImgP varchar(500))
	BEGIN
    if((SELECT count(Nome) FROM Prodotto WHERE Nome=NomeP)>0 ) THEN
		UPDATE Prodotto SET Img=ImgP WHERE Nome=NomeP;
        COMMIT;
    END IF;
    END
| delimiter ;

# store procedure --> modifica Sezione Prodotto
start transaction;
 delimiter |
 CREATE PROCEDURE ModificaSezioneProdotto(NomeP varchar(100),SezioneP varchar(500))
	BEGIN
    if((SELECT count(Nome) FROM Prodotto WHERE Nome=NomeP)>0 ) THEN
		UPDATE Prodotto SET Sezione=SezioneP WHERE Nome=NomeP;
        COMMIT;
    END IF;
    END
| delimiter ;

# store procedure --> modifica tutto un Prodotto
start transaction;
 delimiter |
 CREATE PROCEDURE ModificaProdotto(NomeP varchar(100),PrezzoP decimal(8,2),QuantitaP mediumint(9),
 DescrizioneP text, ImgP varchar(500), SezioneP enum('Cibo','ProdottiSanitari','Accessoristica','Cuccioli'))
	BEGIN
    if((SELECT count(Nome) FROM Prodotto WHERE Nome=NomeP)>0 ) THEN
		UPDATE Prodotto SET Sezione=SezioneP,Img=ImgP,Descrizione=DescrizioneP,Quantita=QuantitaP,Prezzo=PrezzoP
        WHERE Nome=NomeP;
        COMMIT;
    END IF;
    END
| delimiter ;

# store procedure --> modifica Nome Prodotto
start transaction;
 delimiter |
 CREATE PROCEDURE ModificaNomeProdotto(NomeProd varchar(100),NomeNuovoProd varchar(100))
	BEGIN
    if((SELECT count(Nome) FROM Prodotto WHERE Nome=NomeProd)>0 ) THEN
		UPDATE Prodotto SET Nome=NomeNuovoProd
        WHERE Nome=NomeProd;
        COMMIT;
    END IF;
    END
| delimiter ;

# store procedure --> modifica Nome di una Categoria
start transaction;
 delimiter |
 CREATE PROCEDURE ModificaCategoria(NomeCat varchar(100),NomeNuovoCat varchar(100))
	BEGIN
    if((SELECT count(Nome) FROM Categoria WHERE Nome=NomeCat)>0 ) THEN
		UPDATE Categoria SET Nome=NomeNuovoCat
        WHERE Nome=NomeCat;
        COMMIT;
    END IF;
    END
| delimiter ;

# store procedure --> inserisci un prodotto nel carrello
start transaction;
 delimiter |
 CREATE PROCEDURE AggiungiAlCarrello(Username varchar(30), Prodotto varchar(100))
	BEGIN
    if((SELECT count(NomeProdotto) FROM CARRELLO WHERE (UsernameUtente = Username) AND (NomeProdotto = Prodotto)) <= 0) then
		INSERT INTO CARRELLO SET UsernameUtente = Username, NomeProdotto = Prodotto, Quantità = 1;
		COMMIT;
	else UPDATE CARRELLO SET Quantità = Quantità + 1 WHERE (UsernameUtente = Username) AND (NomeProdotto = Prodotto);
    end if;
    END
| delimiter ;
| delimiter ;

# store procedure --> elimina prodotto
start transaction;
 delimiter |
 CREATE PROCEDURE TogliProdottoDalCarrello(Username varchar(30), Prodotto varchar(100))
	BEGIN
    if((SELECT count(Quantità) FROM CARRELLO WHERE (UsernameUtente = Username) AND (NomeProdotto = Prodotto) AND (Quantità = 1)) > 0) then
		DELETE FROM CARRELLO WHERE (UsernameUtente = Username) AND (NomeProdotto = Prodotto) AND (Quantità = 1);
		COMMIT;
	else if((SELECT count(Quantità) FROM CARRELLO WHERE (UsernameUtente = Username) AND (NomeProdotto = Prodotto) AND (Quantità > 1)) > 0) then
		UPDATE CARRELLO SET Quantità = Quantità - 1 WHERE (UsernameUtente = Username) AND (NomeProdotto = Prodotto);
	end if;
    end if;
    END
| delimiter ;

# store procedure --> acquista prodotti nel carrello
start transaction;
 delimiter |
 CREATE PROCEDURE Acquista(Username varchar(30))
	BEGIN
    SET SQL_SAFE_UPDATES = 0;
    DELETE FROM CARRELLO WHERE (UsernameUtente = Username);
    SET SQL_SAFE_UPDATES = 1;
    COMMIT;
    END
| delimiter ;

###################################################### BACHECA #################################################################

# store procedure --> Inserisci Messaggio
start transaction;
 delimiter |
 CREATE PROCEDURE InserisciMessaggio(Messaggio BLOB)
	BEGIN
    INSERT INTO MESSAGGIO SET Messaggio=Messaggio;
    COMMIT;
    END
| delimiter ;

# store procedure --> Inserisci Immagine
start transaction;
 delimiter |
 CREATE PROCEDURE InserisciImmagine(Immagine BLOB)
	BEGIN
    INSERT INTO MESSAGGIO SET Immagine=Immagine;
    COMMIT;
    END
| delimiter ;

###################################################### VIEWS #################################################################
