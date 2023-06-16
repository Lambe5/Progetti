package com.google.gwt.ProgettoIngegneria.client;

import java.io.Serializable;

public class Studente extends Account implements Serializable {
		
	public Studente() {
		super(TipoUtente.STUDENTE);
	}

	public Studente(String email, String password, String nome, String cognome) {
		super(email, password, nome, cognome, TipoUtente.STUDENTE);
	}
	
}