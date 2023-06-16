package com.google.gwt.ProgettoIngegneria.client;

import java.io.Serializable;

public class Segreteria extends Account implements Serializable{

	private Segreteria() {
		super(TipoUtente.SEGRETERIA);
	}
	
	public Segreteria(String email, String password, String nome, String cognome) {
		super(email, password, nome, cognome, TipoUtente.SEGRETERIA);
	}

}
