package com.google.gwt.ProgettoIngegneria.client;

import java.io.Serializable;

public class Docente extends Account implements Serializable{

	private Docente() {
		super(TipoUtente.DOCENTE);
	}

	public Docente(String email, String password, String nome, String cognome) {
		super(email, password, nome, cognome, TipoUtente.DOCENTE);
	}

}