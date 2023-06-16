package com.google.gwt.ProgettoIngegneria.client;

import java.io.Serializable;
import java.util.Date;

public class Corso implements Serializable {
	
	private String nome;
	private Date initDate;
	private Date endDate;
	private String descrizione;
	private String emailDocente;
	private String emailCodocente; 
	
	
	int nextId = 0;
	final int id;

	public Corso() {
		nextId++;
		this.id = nextId;
	}
	
	public Corso(String nome, Date initDate, Date endDate, String descrizione, String emailDocente, String emailCodocente) {
		this.nome = nome;
		this.initDate = initDate;
		this.endDate = endDate;
		this.descrizione = descrizione;
		this.emailDocente = emailDocente;
		this.emailCodocente = emailCodocente;
		
		nextId++;
		this.id = nextId;
	}
	//GETTER
	public String getNome() {
		return nome;
	}
	public String getEmailDocente() {
		return emailDocente;
	}
	
	public Date getInitDate() {
		return initDate;
	}
	
	public Date getEndDate() {
		return endDate;
	}
	
	public String getDescrizione() {
		return descrizione;
	}
	
	public String getEmailCodocente() {
		return emailCodocente;
	}
	
	//SETTER
	
	public void setNome(String newNome) {
		this.nome = newNome;
	}
	
	public void setInitDate(Date newInitDate) {
		this.initDate = newInitDate;
	}
	
	public void setEndDate(Date newEndDate) {
		this.endDate = newEndDate;
	}
	
	public void setDescrizione(String newDescrizione) {
		this.descrizione = newDescrizione;
	}
	
	public void setEmailCodocente(String newEmailCodocente) {
		this.emailCodocente = newEmailCodocente;
	}
}