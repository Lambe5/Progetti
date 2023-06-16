DROP DATABASE IF EXISTS CONFVIRTUAL;
CREATE DATABASE CONFVIRTUAL;
USE CONFVIRTUAL;

SET GLOBAL event_scheduler = ON;

CREATE TABLE UTENTE(
		Username 	 varchar(30) primary key NOT NULL,
        Password 	 varchar(30) NOT NULL,
		Nome 		 varchar(30),
		Cognome 	 varchar(30),
		LuogoNascita varchar(30),
		DataNascita	 Date
        
) ENGINE = INNODB;
 
CREATE TABLE PRESENTER(
		UsernameUtente varchar(30) primary key,
		NomeUni 	   varchar(30),
		NomeDip 	   varchar(30),
		CV 			   varchar(30),
		Foto 		   MEDIUMBLOB,
        
        foreign key(UsernameUtente) references UTENTE(Username)
        
) ENGINE = INNODB;
 
CREATE TABLE SPEAKER(
		UsernameUtente varchar(30) primary key,
		NomeUni 	   varchar(30),
		NomeDip 	   varchar(30),
		CV 			   varchar(30),
		Foto 		   MEDIUMBLOB,
        
        foreign key(UsernameUtente) references UTENTE(Username)
        
) ENGINE = INNODB;
 
CREATE TABLE AMMINISTRATORE(
		UsernameUtente varchar(30) primary key,
		
        foreign key(UsernameUtente) references UTENTE(Username)
        
) ENGINE = INNODB;
 
CREATE TABLE SPONSOR(
		Nome 	varchar(30) primary key,
		ImgLogo MEDIUMBLOB
        
) ENGINE = INNODB;
 
CREATE TABLE CONFERENZA(
		Acronimo 	 varchar(30),
		AnnoEdizione YEAR,
		ImgLogo 	 MEDIUMBLOB,
		Nome 		 varchar(30) NOT NULL,
		Svolgimento  ENUM("Attiva", "Completata") DEFAULT "Attiva",
		
        primary key(Acronimo, AnnoEdizione)
        
) ENGINE = INNODB;
 
CREATE TABLE SPONSORIZZAZIONE(
		NomeSponsor 		   varchar(30),
		AcronimoConferenza     varchar(30),
		AnnoEdizioneConferenza YEAR,
		Importo 			   float NOT NULL, 
		
        primary key(NomeSponsor, AcronimoConferenza, AnnoEdizioneConferenza),
		
        foreign key(AcronimoConferenza, AnnoEdizioneConferenza) references CONFERENZA(Acronimo, AnnoEdizione) on delete cascade,
		foreign key(NomeSponsor) references SPONSOR(Nome) on delete cascade
        
) ENGINE = INNODB;

CREATE TABLE PROGRAMMA_GIORNALIERO(
		Id integer auto_increment, 
		AcronimoConferenza varchar(30) references CONFERENZA(Acronimo) on delete cascade,
        AnnoEdizioneConferenza varchar(30) references CONFERENZA(AnnoEdizione) on delete cascade,
		Data 			   date,
		
        primary key(Id)
		        
) ENGINE = INNODB;
 
CREATE TABLE SESSIONE(
		Codice 			 varchar(10)   primary key,
		IdProgramma 	 integer NOT NULL references PROGRAMMA_GIORNALIERO(Id) on delete cascade,
		LinkTeams 		 varchar(100),
		NumPresentazioni int DEFAULT 0,
		OraIni 			 time,
		OraFine 		 time,
		Titolo 			 varchar(100)
        
) ENGINE = INNODB; 
 
CREATE TABLE MESSAGGIO(
		CodiceSessione  varchar(10),
		Timestamp 	    float,
		UsernameUtente  varchar(30)   NOT NULL,
		Testo 		    varchar(500),
		DataInserimento date,
		
        primary key (CodiceSessione, Timestamp),
        
        foreign key(CodiceSessione) references SESSIONE(Codice) on delete cascade,
        foreign key(UsernameUtente) references UTENTE(Username)
        
) ENGINE = INNODB;
 
CREATE TABLE PRESENTAZIONE(
		Codice 		   varchar(10),
		CodiceSessione varchar(10),
		NumSequenza    int,
        OraIni 		   time,
		OraFine 	   time,
		
		
        primary key(Codice, CodiceSessione),
        
        foreign key(CodiceSessione) references SESSIONE(Codice) on delete cascade
        
) ENGINE = INNODB;
 
 CREATE TABLE ARTICOLO(
		CodicePresentazione 		varchar(10),
		CodiceSessionePresentazione varchar(10),
		Numpagine 		    		int,
		filePDF 		    		MEDIUMBLOB,
		Titolo 			    		varchar(100),
		StatoSvolgimento    		ENUM("Coperto", "NonCoperto") DEFAULT "NonCoperto",
		UsernamePresenter   		varchar(30),
        
		primary key(CodicePresentazione, CodiceSessionePresentazione),
        
        foreign key(CodicePresentazione, CodiceSessionePresentazione) references PRESENTAZIONE(Codice, CodiceSessione) on delete cascade,
        foreign key(UsernamePresenter) references PRESENTER(UsernameUtente)
        
) ENGINE = INNODB;
 
CREATE TABLE TUTORIAL(
		CodicePresentazione 		varchar(10),
		CodiceSessionePresentazione varchar(10),
		Titolo 						varchar(100),
		Abstract 					varchar(500), 
		
        primary key(CodicePresentazione, CodiceSessionePresentazione),
		
        foreign key(CodicePresentazione, CodiceSessionePresentazione) references PRESENTAZIONE(Codice, CodiceSessione) on delete cascade
) ENGINE = INNODB; 
 
CREATE TABLE AUTORE(
		ID 		varchar(30)	primary key,
		Nome 	varchar(30) NOT NULL,
		Cognome varchar(30) NOT NULL
) ENGINE = INNODB;
 
CREATE TABLE LISTA_AUTORI(
		IdAutore 			   varchar(30),
		CodiceArticolo 		   varchar(10),
		CodiceSessioneArticolo varchar(10),
        
		primary key(IdAutore, CodiceArticolo, CodiceSessioneArticolo),
        
		foreign key(CodiceArticolo, CodiceSessioneArticolo) references ARTICOLO(CodicePresentazione, CodiceSessionePresentazione) on delete cascade, 
		foreign key(IdAutore) references AUTORE(ID) on delete cascade
        
) ENGINE = INNODB;
 
CREATE TABLE PAROLA_CHIAVE(
		CodiceArticolo varchar(10) references ARTICOLO(Codice) on delete cascade,
        CodiceSessioneArticolo varchar(10)  references ARTICOLO(Codice) on delete cascade,
		Parola 		   varchar(20), 
        primary key(CodiceArticolo, CodiceSessioneArticolo, Parola)
        
) ENGINE = INNODB;
 
CREATE TABLE LISTA_PRESENTAZIONI_FAVORITE(
		UsernameUtente 				varchar(30),
		CodicePresentazione 		varchar(10),
		CodiceSessionePresentazione varchar(10),
		
        primary key(UsernameUtente, CodicePresentazione, CodiceSessionePresentazione), 
		
        foreign key(UsernameUtente) references UTENTE(Username),
        foreign key(CodicePresentazione, CodiceSessionePresentazione) references PRESENTAZIONE(Codice, CodiceSessione) on delete cascade
        
) ENGINE = INNODB;
 
CREATE TABLE REGISTRAZIONE(
		UsernameUtente 		   varchar(30),
		AcronimoConferenza 	   varchar(30),
		AnnoEdizioneConferenza YEAR,
		
        primary key(UsernameUtente, AcronimoConferenza, AnnoEdizioneConferenza), 
		
        foreign key(UsernameUtente) references UTENTE(Username),
        foreign key(AcronimoConferenza, AnnoEdizioneConferenza) references CONFERENZA(Acronimo, AnnoEdizione) on delete cascade

) ENGINE = INNODB;

 CREATE TABLE CREAZIONE_CONFERENZA(
 		UsernameAmministratore varchar(30),
 		AcronimoConferenza 	   varchar(30),
 		AnnoEdizioneConferenza YEAR,
 		
         primary key(UsernameAmministratore, AcronimoConferenza, AnnoEdizioneConferenza),
         
 		foreign key(UsernameAmministratore) references AMMINISTRATORE(UsernameUtente),
         foreign key(AcronimoConferenza, AnnoEdizioneConferenza) references CONFERENZA(Acronimo, AnnoEdizione) on delete cascade
         
 ) ENGINE = INNODB;
 
CREATE TABLE VALUTAZIONE(
		UsernameAmministratore 		varchar(30),
		CodicePresentazione 		varchar(10),
		CodiceSessionePresentazione varchar(10),
		Voto 						int 		CHECK(Voto >= 0 and Voto <= 10),
		Note 						varchar(50), 
		
        primary key(UsernameAmministratore, CodicePresentazione, CodiceSessionePresentazione), 
		
        foreign key(CodicePresentazione, CodiceSessionePresentazione) references PRESENTAZIONE(Codice, CodiceSessione) on delete cascade, 
		foreign key(UsernameAmministratore) references AMMINISTRATORE(UsernameUtente)
        
) ENGINE = INNODB; 
 
 CREATE TABLE INFO_AGGIUNTIVE(
		UsernameSpeaker 	   varchar(30),
		CodiceTutorial 		   varchar(10),
		CodiceSessioneTutorial varchar(10),
		LinkWeb 			   varchar(100),
		Descrizione 		   varchar(500),
        
		primary key(UsernameSpeaker, CodiceTutorial, CodiceSessioneTutorial),
        
        foreign key(UsernameSpeaker) references SPEAKER(UsernameUtente),
		foreign key(CodiceTutorial, CodiceSessioneTutorial) references TUTORIAL(CodicePresentazione, CodiceSessionePresentazione) on delete cascade
        
) ENGINE = INNODB;
 
CREATE TABLE PRESENTAZIONE_TUTORIAL(
		UsernameSpeaker 		varchar(30),
		CodiceTutorial 			varchar(10),
		CodiceSessioneTutorial 	varchar(10),
		
        primary key(UsernameSpeaker, CodiceTutorial, CodiceSessioneTutorial),
        
		foreign key(UsernameSpeaker) references SPEAKER(UsernameUtente),
        foreign key(CodiceTutorial, CodiceSessioneTutorial) references TUTORIAL(CodicePresentazione, CodiceSessionePresentazione) on delete cascade
        
) ENGINE = INNODB;

INSERT INTO UTENTE (Username, Password, Nome, Cognome, LuogoNascita, DataNascita) 
values ("Aut", "123", "Dario", "Bianchi", "Bologna", "2000-10-10");

INSERT INTO UTENTE (Username, Password, Nome, Cognome, LuogoNascita, DataNascita) 
values ("Sp1", "123", "Alice", "Bruna", "Bologna", "2000-10-10");

INSERT INTO UTENTE (Username, Password, Nome, Cognome, LuogoNascita, DataNascita) 
values ("Pres1", "123", "Alessanda", "Pasticcio", "Bologna", "2000-10-10");

INSERT INTO UTENTE (Username, Password, Nome, Cognome, LuogoNascita, DataNascita) 
values ("CarloAm", "123", "Carlo", "Rossi", "Bologna", "2000-10-10");

INSERT INTO PRESENTER (UsernameUtente, NomeUni, NomeDip, CV, Foto)
values ("Pres1", "Unibo", "InfoMan", "CV1", "Foto1");

INSERT INTO AMMINISTRATORE (UsernameUtente)
values ("CarloAm");

INSERT INTO SPEAKER (UsernameUtente, NomeUni, NomeDip, CV, Foto) 
values ("Sp1", "Unibo", "Informatica", "CV1", "foto1");

INSERT INTO CONFERENZA (Acronimo, AnnoEdizione, ImgLogo, Nome)
values ("Conf1", 2022, "", "Conferenza1");

INSERT INTO PROGRAMMA_GIORNALIERO (AcronimoConferenza, AnnoEdizioneConferenza, Data)
values ("Conf1", 2022, "2022-10-10");

INSERT INTO SESSIONE (Codice, IdProgramma, LinkTeams, NumPresentazioni, OraIni, OraFine, Titolo)
values ("A1", 1, "link1", "2", "09:00", "11:00", "Tecnologia");

INSERT INTO PRESENTAZIONE (Codice, CodiceSessione, NumSequenza, OraIni, OraFine)
values ("T1", "A1", "1", "09:00", "11:00");

INSERT INTO PRESENTAZIONE (Codice, CodiceSessione, NumSequenza, OraIni, OraFine)
values ("Art1", "A1", "1", "09:00", "11:00");

INSERT INTO TUTORIAL (CodicePresentazione, CodiceSessionePresentazione, Titolo, Abstract)
values ("T1", "A1", "TitoloTut", "Abstract...");

INSERT INTO ARTICOLO (CodicePresentazione, CodiceSessionePresentazione, Numpagine, Titolo, StatoSvolgimento, UsernamePresenter)
values ("Art1", "A1", "10", "TitoloArt", "Coperto", "Pres1");

INSERT INTO AUTORE (ID, Nome, Cognome)
values ("Pres1", "Alessandra", "Pasticcio");

INSERT INTO LISTA_AUTORI (IdAutore, CodiceArticolo, CodiceSessioneArticolo)
values ("Pres1", "Art1", "A1");

INSERT INTO PRESENTAZIONE_TUTORIAL (UsernameSpeaker, CodiceTutorial, CodiceSessioneTutorial)
values ("Sp1", "T1", "A1");

INSERT INTO REGISTRAZIONE (UsernameUtente, AcronimoConferenza, AnnoEdizioneConferenza)
values ("Sp1", "Conf1", 2022);

INSERT INTO REGISTRAZIONE (UsernameUtente, AcronimoConferenza, AnnoEdizioneConferenza)
values ("Pres1", "Conf1", 2022);

INSERT INTO REGISTRAZIONE (UsernameUtente, AcronimoConferenza, AnnoEdizioneConferenza)
values ("CarloAm", "Conf1", 2022);

INSERT INTO CREAZIONE_CONFERENZA (UsernameAmministratore, AcronimoConferenza, AnnoEdizioneConferenza)
values ("CarloAm", "Conf1", 2022);
#Lista stored procedure
/********************************************************************************************************************************/
#Stored procedure --> crea Conferenza (Controllo che l'annoEdizione sia maggiore della data corrente)
start transaction;
delimiter |
CREATE PROCEDURE CreaConferenza(Acronimo varchar(30),AnnoEdizione YEAR,ImgLogo MEDIUMBLOB,Nome varchar(30))
	BEGIN
    IF(AnnoEdizione >= YEAR(NOW()) AND AnnoEdizione < 2155 ) THEN
			INSERT INTO CONFERENZA SET Acronimo = Acronimo, AnnoEdizione = AnnoEdizione, ImgLogo = ImgLogo, Nome = Nome;
	END IF;
	END;
| delimiter ;
commit;

#Stored procedure
start transaction;
delimiter |
CREATE PROCEDURE AssociaAmministratore(UsernameAmministratore varchar(30), AcronimoConferenza varchar(30), AnnoEdizioneConferenza YEAR)
	BEGIN
			INSERT INTO registrazione SET UsernameUtente = UsernameAmministratore, AcronimoConferenza = AcronimoConferenza, AnnoEdizioneConferenza = AnnoEdizioneConferenza;
	END;
| delimiter ;
commit;

#Stored procedure --> associa admin a conferenza creata
start transaction;
delimiter |
CREATE PROCEDURE AssociaCreazioneConf(UsernameAmministratore varchar(30), AcronimoConferenza varchar(30), AnnoEdizioneConferenza YEAR)
	BEGIN
			INSERT INTO CREAZIONE_CONFERENZA SET UsernameAmministratore = UsernameAmministratore, AcronimoConferenza = AcronimoConferenza, AnnoEdizioneConferenza = AnnoEdizioneConferenza;
	END;
| delimiter ;
commit;

#Stored procedure --> crea programma_giornaliero
start transaction;
 delimiter |
 CREATE PROCEDURE CreaProgrammaGiornaliero(AcronimoConferenza varchar(30), AnnoEdizioneConferenza varchar(30), Data date)
	BEGIN
    INSERT INTO PROGRAMMA_GIORNALIERO SET AcronimoConferenza = AcronimoConferenza, AnnoEdizioneConferenza = AnnoEdizioneConferenza, Data = Data;
    COMMIT;
    END
| delimiter ;

#Stored procedure --> crea Sessione
start transaction;
delimiter |
CREATE PROCEDURE CreaSessione(IN Codice varchar(10), IN IdProgramma integer,  IN LinkTeams varchar(100),IN NumPresentazioni int(11), IN OraIni time, IN OraFine time, IN Titolo varchar(100))
	begin
		IF(OraIni < OraFine &&(SELECT count(PROGRAMMA_GIORNALIERO.Id) 
								 FROM PROGRAMMA_GIORNALIERO 
								WHERE PROGRAMMA_GIORNALIERO.Id = IdProgramma) > 0)
		THEN
			INSERT INTO SESSIONE 
			SET Codice = Codice, IdProgramma = IdProgramma, LinkTeams = LinkTeams,NumPresentazioni=NumPresentazioni, OraIni = OraIni, OraFine = OraFine, Titolo = Titolo;
			COMMIT;
		ELSE ROLLBACK;
		END IF;
	END;
| delimiter ;

#Stored procedure --> crea Presentazione
start transaction;
delimiter |
CREATE PROCEDURE CreaPresentazione(Codice varchar(10), CodiceSessione varchar(10), NumSequenza int, NewOraIni time, NewOraFine time )
	begin
		IF((SELECT count(SESSIONE.Codice) 
			  FROM SESSIONE
			 WHERE (SESSIONE.Codice = CodiceSessione) 
			   AND (NewOraFine <= SESSIONE.OraFine) 
			   AND (NewOraIni >= SESSIONE.OraIni) 
               AND NewOraIni < NewOraFine)) > 0
		THEN
			INSERT INTO PRESENTAZIONE 
            SET Codice = Codice, CodiceSessione = CodiceSessione, NumSequenza = NumSequenza, OraFine = NewOraFine, OraIni = NewOraIni;
			COMMIT;
		ELSE ROLLBACK;
		END IF;
	END;
| delimiter ;

 # Stored procedure --> associa speaker - tutorial
 start transaction;
delimiter |
CREATE PROCEDURE AssociaSpeaker(UsernameSpeaker varchar(30), CodiceTutorial varchar(10), CodiceSessioneTutorial varchar(10))
	BEGIN
		if(SELECT count(SPEAKER.UsernameUtente) FROM SPEAKER WHERE SPEAKER.UsernameUtente = UsernameSpeaker) > 0 THEN
        INSERT INTO PRESENTAZIONE_TUTORIAL
        SET UsernameSpeaker = UsernameSpeaker, CodiceTutorial = CodiceTutorial, CodiceSessioneTutorial = CodiceSessioneTutorial;
        COMMIT;
        end if;
    END
 | delimiter ;
 /********************************************************************************************************************************/
 #Stored procedure --> Associa un presenter alla presentazione di un articolo
 start transaction;
 delimiter |
 CREATE PROCEDURE AssociaPresenter(CodicePresentazione varchar(10),CodiceSessionePresentazione varchar(10),UsernamePresenter varchar(30))
 BEGIN
 if((SELECT count(ARTICOLO.CodicePresentazione) FROM ARTICOLO WHERE 
 ((ARTICOLO.CodicePresentazione=CodicePresentazione) and (ARTICOLO.CodiceSessionePresentazione=CodiceSessionePresentazione)))>0 AND 
 (SELECT count(PRESENTER.UsernameUtente) FROM PRESENTER WHERE (PRESENTER.UsernameUtente=UsernamePresenter))>0 ) THEN
 UPDATE ARTICOLO
 SET 
 UsernamePresenter=UsernamePresenter
 WHERE
 CodicePresentazione=CodicePresentazione AND CodiceSessionePresentazione=CodiceSessionePresentazione;
 COMMIT;
 end if;
 END  
 |delimiter;
 
 # Stored procedure --> crea Utente, utile per la registrazione di un nuovo utente
 start transaction;
 delimiter |
 CREATE PROCEDURE CreaUtente(Username varchar(30), Password varchar(30), Nome varchar(30), Cognome varchar(30), LuogoNascita varchar(30), DataNascita Date)
	BEGIN
    INSERT INTO UTENTE SET  Username = Username, Password = Password, Nome = Nome, Cognome = Cognome, LuogoNascita = LuogoNascita, DataNascita = DataNascita;
    COMMIT;
    END
| delimiter ;

# Stored procedure --> crea Speaker
 start transaction;
 delimiter |
 CREATE PROCEDURE CreaSpeaker(UsernameUtente varchar(30), NomeUni varchar(30), NomeDip varchar(30), CV varchar(30), Foto MEDIUMBLOB)
	BEGIN
    if(SELECT count(UTENTE.Username) FROM UTENTE WHERE UTENTE.Username = UsernameUtente) > 0 THEN
    INSERT INTO SPEAKER SET  UsernameUtente = UsernameUtente, NomeUni = NomeUni, NomeDip = NomeDip, CV = CV, Foto = Foto;
    COMMIT;
    end if;
    END
| delimiter ;

# Stored procedure --> crea Presenter
 start transaction;
 delimiter |
 CREATE PROCEDURE CreaPresenter(UsernameUtente varchar(30), NomeUni varchar(30), NomeDip varchar(30), CV varchar(30), Foto MEDIUMBLOB)
	BEGIN
    if(SELECT count(UTENTE.Username) FROM UTENTE WHERE UTENTE.Username = UsernameUtente) > 0 THEN
    INSERT INTO PRESENTER SET  UsernameUtente = UsernameUtente, NomeUni = NomeUni, NomeDip = NomeDip, CV = CV, Foto = Foto;
    COMMIT;
    end if;
    END
| delimiter ;

# Stored procedure --> modifica CV da parte dello speaker
 start transaction;
 delimiter |
 CREATE PROCEDURE ModificaCVSpeaker(UsernameUtente varchar(30), CV varchar(30))
	BEGIN
    UPDATE SPEAKER 
    SET CV = CV
    WHERE (SPEAKER.UsernameUtente = UsernameUtente);
    COMMIT;
    END
| delimiter ;

# Stored procedure --> modifica Foto da parte dello speaker
 start transaction;
 delimiter |
 CREATE PROCEDURE ModificaFotoSpeaker(UsernameUtente varchar(30), Foto MEDIUMBLOB)
	BEGIN
    UPDATE SPEAKER 
    SET Foto = Foto
    WHERE (SPEAKER.UsernameUtente = UsernameUtente);
    COMMIT;
    END
| delimiter ;

# Stored procedure --> modifica affiliazione universitaria da parte dello speaker
 start transaction;
 delimiter |
 CREATE PROCEDURE ModificaAffiliazioneSpeaker(UsernameUtente varchar(30), NomeUni varchar(30), NomeDip varchar(30))
	BEGIN
    UPDATE SPEAKER 
    SET NomeUni = NomeUni, NomeDip = NomeDip
    WHERE (SPEAKER.UsernameUtente = UsernameUtente);
    COMMIT;
    END
| delimiter ;

# Stored procedure --> modifica CV da parte del presenter
 start transaction;
 delimiter |
 CREATE PROCEDURE ModificaCVPresenter(UsernameUtente varchar(30), CV varchar(30))
	BEGIN
    UPDATE PRESENTER 
    SET CV = CV
    WHERE (PRESENTER.UsernameUtente = UsernameUtente);
    COMMIT;
    END
| delimiter ;

# Stored procedure --> modifica Foto da parte del presenter
 start transaction;
 delimiter |
 CREATE PROCEDURE ModificaFotoPresenter(UsernameUtente varchar(30), Foto MEDIUMBLOB)
	BEGIN
    UPDATE PRESENTER 
    SET Foto = Foto
    WHERE (PRESENTER.UsernameUtente = UsernameUtente);
    COMMIT;
    END
| delimiter ;

# Stored procedure --> modifica affiliazione universitaria da parte del presenter
 start transaction;
 delimiter |
 CREATE PROCEDURE ModificaAffiliazionePresenter(UsernameUtente varchar(30), NomeUni varchar(30), NomeDip varchar(30))
	BEGIN
    UPDATE PRESENTER 
    SET NomeUni = NomeUni, NomeDip = NomeDip
    WHERE (PRESENTER.UsernameUtente = UsernameUtente);
    COMMIT;
    END
| delimiter ;

# Stored procedure --> creazione di un tutorial
 start transaction;
 delimiter |
 CREATE PROCEDURE CreaTutorial(CodicePresentazione varchar(10), CodiceSessionePresentazione varchar(10), Titolo varchar(100), Abstract varchar(500))
	BEGIN
		INSERT INTO TUTORIAL 
		SET CodicePresentazione = CodicePresentazione, CodiceSessionePresentazione = CodiceSessionePresentazione, Titolo = Titolo, Abstract = Abstract;
		COMMIT;
    END
| delimiter ;

# Stored procedure --> crea info aggiuntive
 start transaction;
 delimiter |
 CREATE PROCEDURE CreaInfoAggiuntive(UsernameSpeaker varchar(30), CodiceTutorial varchar(10), CodiceSessioneTutorial varchar(10), LinkWeb varchar(100), Descrizione varchar(500))
	BEGIN
		INSERT INTO INFO_AGGIUNTIVE 
		SET UsernameSpeaker = UsernameSpeaker, CodiceTutorial = CodiceTutorial, CodiceSessioneTutorial = CodiceSessioneTutorial, LinkWeb = LinkWeb, Descrizione = Descrizione;
		COMMIT;
    END
| delimiter ;

# Stored procedure --> inserisci o modifica il link in info_aggiuntive
 start transaction;
 delimiter |
 CREATE PROCEDURE ModificaLinkInfoAggiuntive(UsernameSpeaker varchar(30), CodiceTutorial varchar(10), CodiceSessioneTutorial varchar(10), LinkWeb varchar(100))
	BEGIN
    UPDATE INFO_AGGIUNTIVE 
    SET LinkWeb = LinkWeb
    WHERE (INFO_AGGIUNTIVE.UsernameSpeaker = UsernameSpeaker) AND (INFO_AGGIUNTIVE.CodiceTutorial = CodiceTutorial) AND (INFO_AGGIUNTIVE.CodiceSessioneTutorial = CodiceSessioneTutorial);
    COMMIT;
    END
| delimiter ;

# Stored procedure --> inserisci o modifica la descrizione in info_aggiuntive
 start transaction;
 delimiter |
 CREATE PROCEDURE ModificaDescrizioneInfoAggiuntive(UsernameSpeaker varchar(30), CodiceTutorial varchar(10), CodiceSessioneTutorial varchar(10), Descrizione varchar(500))
	BEGIN
    UPDATE INFO_AGGIUNTIVE 
    SET Descrizione = Descrizione
    WHERE (INFO_AGGIUNTIVE.UsernameSpeaker = UsernameSpeaker) AND (INFO_AGGIUNTIVE.CodiceTutorial = CodiceTutorial) AND (INFO_AGGIUNTIVE.CodiceSessioneTutorial = CodiceSessioneTutorial);
    COMMIT;
    END
| delimiter ;

# Stored procedure --> registrazione a una conferenza
 start transaction;
 delimiter |
 CREATE PROCEDURE RegistrazioneConferenza(UsernameUtente varchar(30), AcronimoConferenza varchar(30), AnnoEdizioneConferenza YEAR)
	BEGIN
    INSERT INTO REGISTRAZIONE 
    SET UsernameUtente = UsernameUtente, AcronimoConferenza = AcronimoConferenza, AnnoEdizioneConferenza = AnnoEdizioneConferenza;
    COMMIT;
    END
| delimiter ;
#Store Procedure --> Crea Articolo
start transaction;
delimiter |
CREATE PROCEDURE CreaArticolo(CodicePresentazione varchar(10), CodiceSessionePresentazione varchar(10), Numpagine int(11),
				 filePDF MEDIUMBLOB, Titolo varchar(100))
 BEGIN
	if(SELECT count(CodicePresentazione) 
         FROM PRESENTAZIONE 
		WHERE ((CodicePresentazione = CodicePresentazione) 
	      and (CodiceSessionePresentazione = CodiceSessionePresentazione)) > 0)
	then
			INSERT INTO ARTICOLO
			SET CodicePresentazione = CodicePresentazione,
			CodiceSessionePresentazione = CodiceSessionePresentazione,
			Numpagine = Numpagine,
			filePDF = filePDF,
			Titolo = Titolo,
			StatoSvolgimento = "NonCoperto";
			COMMIT;
	end if;
 END
| delimiter;

# Stored procedure --> inserimento lista presentazioni favorite
 start transaction;
 delimiter |
 CREATE PROCEDURE InserisciPresentazionePreferitaInLista(UsernameUtente varchar(30), CodicePresentazione varchar(10), CodiceSessionePresentazione varchar(10))
	BEGIN
    INSERT INTO LISTA_PRESENTAZIONI_FAVORITE 
    SET UsernameUtente = UsernameUtente, CodicePresentazione = CodicePresentazione, CodiceSessionePresentazione = CodiceSessionePresentazione;
    COMMIT;
    END
| delimiter ;

# Stored procedure --> inserimento sponsor
 start transaction;
 delimiter |
 CREATE PROCEDURE InserisciSponsor(Nome varchar(30), ImgLogo MEDIUMBLOB)
	BEGIN
    INSERT INTO SPONSOR
    SET Nome = Nome, ImgLogo = ImgLogo;
    COMMIT;
    END
| delimiter ;

# Stored procedure --> inserimento sponsorizzazione
 start transaction;
 delimiter |
 CREATE PROCEDURE InserisciSponsorizzazione(NomeSponsor varchar(30), AcronimoConferenza varchar(30), AnnoEdizioneConferenza YEAR, Importo float)
	BEGIN
    INSERT INTO SPONSORIZZAZIONE
    SET NomeSponsor = NomeSponsor, AcronimoConferenza = AcronimoConferenza, AnnoEdizioneConferenza = AnnoEdizioneConferenza, Importo = Importo;
    COMMIT;
    END
| delimiter ;

# Stored procedure --> inserisci autore
 start transaction;
 delimiter |
 CREATE PROCEDURE InserisciAutore(ID varchar(30), Nome varchar(30), Cognome varchar(30), CodiceArticolo varchar(10), CodiceSessioneArticolo varchar(10))
	BEGIN
		INSERT INTO AUTORE
		SET ID = ID, Nome = Nome, Cognome = Cognome;
		INSERT INTO LISTA_AUTORI
		SET IdAutore = ID, CodiceArticolo = CodiceArticolo, CodiceSessioneArticolo = CodiceSessioneArticolo;
		COMMIT;
    END
| delimiter ;


# Stored procedure --> inserisci autore nella lista
 start transaction;
 delimiter |
 CREATE PROCEDURE InserisciListaAutori(IdAutore varchar(30), CodiceArticolo varchar(10), CodiceSessioneArticolo varchar(10))
	BEGIN
    INSERT INTO LISTA_AUTORI
    SET IdAutore = IdAutore, CodiceArticolo = CodiceArticolo, CodiceSessioneArticolo = CodiceSessioneArticolo;
    COMMIT;
    END
| delimiter ;

# Stored procedure --> inserimento admin
 start transaction;
 delimiter |
 CREATE PROCEDURE InserisciAmministratore(UsernameUtente varchar(30))
	BEGIN
    INSERT INTO AMMINISTRATORE
    SET UsernameUtente = UsernameUtente;
    COMMIT;
    END
| delimiter ;

# Stored procedure --> inserimento messaggio
 start transaction;
 delimiter |
 CREATE PROCEDURE InserisciMessaggio(CodiceSessione varchar(10),Timestamp float,  UsernameUtente varchar(30), Testo varchar(500), DataInserimento date)
	BEGIN
    INSERT INTO MESSAGGIO
    SET CodiceSessione = CodiceSessione, Timestamp = Timestamp, UsernameUtente = UsernameUtente, Testo = Testo, DataInserimento = DataInserimento;
    COMMIT;
    END
| delimiter ;

# Stored procedure --> inserimento valutazione
 start transaction;
 delimiter |
 CREATE PROCEDURE InserisciValutazione(UsernameAmministratore varchar(30), CodicePresentazione varchar(10), CodiceSessionePresentazione varchar(10), Voto int, Note varchar(50))
	BEGIN
    INSERT INTO VALUTAZIONE
    SET UsernameAmministratore = UsernameAmministratore, CodicePresentazione = CodicePresentazione, CodiceSessionePresentazione = CodiceSessionePresentazione, Voto = Voto, Note = Note;
    COMMIT;
    END
| delimiter ;

# Stored procedure --> inserimento parola chiave
 start transaction;
 delimiter |
 CREATE PROCEDURE InserisciParolaChiave(CodiceArticolo varchar(10), CodiceSessioneArticolo varchar(10), Parola varchar(20))
	BEGIN
    INSERT INTO PAROLA_CHIAVE
    SET CodiceArticolo = CodiceArticolo, CodiceSessioneArticolo = CodiceSessioneArticolo, Parola = Parola;
    COMMIT;
    END
| delimiter ;

# Stored procedure --> elimina conferenza
 start transaction;
 delimiter |
 CREATE PROCEDURE EliminaConferenza(Acronimo varchar(30), AnnoEdizione YEAR)
	BEGIN
    DELETE FROM CONFERENZA
	WHERE (CONFERENZA.Acronimo = Acronimo) AND (CONFERENZA.AnnoEdizione = AnnoEdizione);
    COMMIT;
    END
| delimiter ;

# Stored procedure --> elimina sessione
 start transaction;
 delimiter |
 CREATE PROCEDURE EliminaSessione(Codice varchar(10))
	BEGIN
    DELETE FROM SESSIONE
	WHERE (SESSIONE.Codice = Codice);
    COMMIT;
    END
| delimiter ;

# Stored procedure --> elimina presentazione
 start transaction;
 delimiter |
 CREATE PROCEDURE EliminaPresentazione(Codice varchar(10), CodiceSessione varchar(10))
	BEGIN
    DELETE FROM PRESENTAZIONE
	WHERE (PRESENTAZIONE.Codice = Codice) AND (PRESENTAZIONE.CodiceSessione = CodiceSessione);
    COMMIT;
    END
| delimiter ;

# Stored procedure --> elimina info aggiuntive
 start transaction;
 delimiter |
 CREATE PROCEDURE EliminaInfoAggiuntive(UsernameSpeaker varchar(30), CodiceTutorial varchar(10), CodiceSessioneTutorial varchar(10))
	BEGIN
    DELETE FROM INFO_AGGIUNTIVE
	WHERE (INFO_AGGIUNTIVE.UsernameSpeaker = UsernameSpeaker) AND (INFO_AGGIUNTIVE.CodiceTutorial = CodiceTutorial) AND (INFO_AGGIUNTIVE.CodiceSessioneTutorial = CodiceSessioneTutorial);
    COMMIT;
    END
| delimiter ;

# Stored procedure
 start transaction;
 delimiter |
 CREATE PROCEDURE CreaNuovoArticolo(Codice varchar(10), CodiceSessione varchar(10), NumSequenza varchar(10),
					OraIni time, OraFine time, Numpagine int(11) ,filePDF MEDIUMBLOB, Titolo varchar(100))
	BEGIN
		SET @Codice = Codice, @CodiceSessione = CodiceSessione, @NumSequenza = NumSequenza, @OraIni = OraIni, @OraFine = OraFine,
			@Numpagine = Numpagine, @filePDF = filePDF, @Titolo = Titolo;
    
		CALL CreaPresentazione(@Codice, @CodiceSessione, @NumSequenza, @OraIni, @OraFine);
        CALL CreaArticolo(@Codice, @CodiceSessione, @Numpagine, @filePDF, @Titolo);
        
    COMMIT;
    END
| delimiter ;

# Stored procedure
 start transaction;
 delimiter |
 CREATE PROCEDURE CreaNuovoTutorial(Codice varchar(10), CodiceSessione varchar(10), NumSequenza varchar(10),
					OraIni time, OraFine time, Titolo varchar(100), Abstract varchar(500))
	BEGIN
		SET @Codice = Codice, @CodiceSessione = CodiceSessione, @NumSequenza = NumSequenza, @OraIni = OraIni,
			@OraFine = OraFine, @Titolo = Titolo, @Abstract = Abstract;
    
		CALL CreaPresentazione(@Codice, @CodiceSessione, @NumSequenza, @OraIni, @OraFine);
        CALL CreaTutorial(@Codice, @CodiceSessione, @Titolo, @Abstract);
        
    COMMIT;
    END
| delimiter ;

# Stored procedure
 start transaction;
 delimiter |
CREATE PROCEDURE CreaEAssociaPresenter(UsernamePresenter varchar(30), CodiceArticolo varchar(10),
				 CodiceSessioneArticolo varchar(10))
	BEGIN
		SET @UsernamePresenter = UsernamePresenter, @Codice = CodiceArticolo, @CodiceSessione = CodiceSessioneArticolo;
    
		IF(SELECT count(UsernameUtente) 
			  FROM PRESENTER
			 WHERE (UsernameUtente = @UsernamePresenter) = 0)
		THEN
			CALL CreaUtente(@UsernamePresenter, "password", null, null, null, null);
			CALL CreaPresenter(@UsernamePresenter, null, null, null, null);
            COMMIT;
		ELSE ROLLBACK;
		END IF;
        
        CALL AssociaPresenter(@Codice, @CodiceSessione, @UsernamePresenter);
        
    COMMIT;
    END
| delimiter ;

/********************************************************************************************************************************/
 

 
 #Lista dei trigger
/********************************************************************************************************************************/
#Trigger --> Aggiorna il numero di presentazioni dentro la tabella SESSIONE
delimiter |
CREATE TRIGGER AggiornaNumeroPresentazioni
		 AFTER INSERT ON PRESENTAZIONE
  FOR EACH ROW
		 BEGIN
				UPDATE SESSIONE
				   SET SESSIONE.NumPresentazioni =  SESSIONE.NumPresentazioni + 1
				 WHERE SESSIONE.Codice = NEW.CodiceSessione;
		   END;
| delimiter ;

#DROP TRIGGER IF EXISTS CambiaStatoSvolgimento;
# trigger : setta stato svolgimento a "Coperto" quando viene associato un Presenter ad un Articolo
delimiter |
CREATE TRIGGER CambiaStatoSvolgimento BEFORE UPDATE ON ARTICOLO
  FOR EACH ROW
  BEGIN
				   SET NEW.StatoSvolgimento = "Coperto";
  END
| delimiter ;

#Lista delle view
/********************************************************************************************************************************/ 

#View | Visualizza i presenter/speaker sulla base del voto medio
delimiter |
CREATE VIEW presenter_speaker_votomedio(Username,VotoMed,Tipo) AS
	 SELECT Utente.Username, AVG(VALUTAZIONE.Voto) AS VotoMed,
     CASE WHEN Utente.Username=Speaker.UsernameUtente THEN "Speaker" 
     ELSE "Presenter" END as TipoUtente
     FROM Utente,Speaker,Presenter,Valutazione,presentazione_tutorial,Articolo
     WHERE ((Utente.Username=Speaker.UsernameUtente) AND (SPEAKER.UsernameUtente = PRESENTAZIONE_TUTORIAL.UsernameSpeaker) AND
		   (PRESENTAZIONE_TUTORIAL.CodiceTutorial = VALUTAZIONE.CodicePresentazione) AND
           (PRESENTAZIONE_TUTORIAL.CodiceSessioneTutorial = VALUTAZIONE.CodiceSessionePresentazione)) # Caso in cui è uno Speaker
           OR 
           ((Utente.Username=Presenter.UsernameUtente) AND (PRESENTER.UsernameUtente = ARTICOLO.UsernamePresenter) AND
		   (ARTICOLO.CodicePresentazione = VALUTAZIONE.CodicePresentazione) AND
           (ARTICOLO.CodiceSessionePresentazione = VALUTAZIONE.CodiceSessionePresentazione)) # Caso in cui è uno Presenter
           group by (Username)
		   ORDER BY VotoMed DESC;
           
| delimiter ;


#View  | Mostra Username e tipo Utente (Escluso utente generale)
delimiter |
CREATE VIEW username_tipoutente(Username,Tipo) AS
	SELECT Username,
    CASE WHEN (Utente.Username=Amministratore.UsernameUtente) THEN 'Amministratore'
		 WHEN (Utente.Username=Presenter.UsernameUtente) THEN 'Presenter'
         ELSE 'Speaker' END AS TipoUtente
    FROM Utente,Amministratore,Speaker,Presenter
    WHERE (Utente.Username=Amministratore.UsernameUtente) OR (Utente.Username=Presenter.UsernameUtente)
		  OR (Utente.Username=Speaker.UsernameUtente)
	group by (Username);
| delimiter ;

/********************************************************************************************************************************/ 


# evento 1: setta svolgimento della conferenza a "Completata" dopo la scadenza
CREATE VIEW dataMax(AnnoEdizioneConferenza,AcronimoConferenza,DataMassima) AS (
	SELECT AnnoEdizioneConferenza, AcronimoConferenza, MAX(Data) AS DataMassima
	FROM PROGRAMMA_GIORNALIERO, CONFERENZA
	WHERE (PROGRAMMA_GIORNALIERO.AcronimoConferenza = CONFERENZA.Acronimo)
	AND (PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza = CONFERENZA.AnnoEdizione)
	GROUP BY PROGRAMMA_GIORNALIERO.AcronimoConferenza, PROGRAMMA_GIORNALIERO.AnnoEdizioneConferenza
);

delimiter |
CREATE EVENT ModificaSvolgimento
ON SCHEDULE EVERY 24 HOUR
DO
	UPDATE CONFERENZA, dataMax
	   SET CONFERENZA.Svolgimento = "Completata"
	 WHERE(CONFERENZA.Acronimo = dataMax.AcronimoConferenza)
		AND (CONFERENZA.AnnoEdizione = dataMax.AnnoEdizioneConferenza)
		AND (now() > dataMax.DataMassima);
| delimiter ;