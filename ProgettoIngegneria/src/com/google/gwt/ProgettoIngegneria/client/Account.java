package com.google.gwt.ProgettoIngegneria.client;

import java.io.Serializable;

public abstract class Account implements Serializable{
	
	protected String email;
	protected String password;
	protected String nome;
	protected String cognome;
	protected TipoUtente utente;
	
	int nextId = 0;
	final int id;
	
	public Account() {
		nextId++;
		this.id = nextId;
	}
	
	public Account(TipoUtente utente) {
		nextId++;
		this.id = nextId;
		
		this.utente = utente;
	}
	
	public Account(String email, String password, String nome, String cognome, TipoUtente utente) {
		nextId++;
		this.id = nextId;
		this.email = email;
		this.password = password;
		this.nome = nome;
		this.cognome = cognome;
		this.utente = utente;
	}
	//METODI GETTER
	public String getNome() {
		return this.nome;
	}
	
	public String getEmail() {
		return this.email;
	}

	public String getPassword() {
		return this.password;
	}
	
	public TipoUtente getUtente() {
		return this.utente;
	}

	public String getCognome() {
		return this.cognome;
	}
	//Metodi SETTER
	public void setEmail(String email) {
		this.email = email;
	}
	
	public void setPassword(String password) {
		this.password = password;
	}
	
	public void setNome(String nome) {
		this.nome = nome;
	}
	public void setCognome(String cognome) {
		this.cognome = cognome;
	}
}