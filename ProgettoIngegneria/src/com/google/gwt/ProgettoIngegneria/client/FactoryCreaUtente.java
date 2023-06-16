package com.google.gwt.ProgettoIngegneria.client;

import java.io.Serializable;

public class FactoryCreaUtente implements Serializable{
	
	protected Account account;
	public Account creaUtente(TipoUtente type, String email, String password, String nome, String cognome) {
		switch(type) {
		case STUDENTE:
			account = new Studente(email, password, nome, cognome);
			break;
		case DOCENTE:
			account = new Docente(email, password, nome, cognome);
			break;
		case SEGRETERIA:
			account = new Segreteria(email, password, nome, cognome);
			break;
		}
		return account;
	}
	
	public Account creaUtente(TipoUtente type) {
		return account;
		
	}
}
