package com.google.gwt.ProgettoIngegneria.client;

import java.io.Serializable;

public class Voto implements Serializable{
	private String studente;
	private String corso;
	private String voto;
	
	public Voto() {
		
	}
	
	public Voto(String studente, String corso) {
		this.studente = studente;
		this.corso = corso;
	}
	//SETTER
	public void setVoto(String voto) {
		this.voto = voto;
	}
	
	//GETTER
	public String getVoto() {
		return this.voto;
	} 
	public String getStudente() {
		return this.studente;
	}
	public String getCorso() {
		return this.corso;
	}
	
}
