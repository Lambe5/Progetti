package com.google.gwt.ProgettoIngegneria.client;

import java.io.Serializable;
import java.util.Date;

public class Esame implements Serializable {
	
	private String emailDocente;
	private Date data;
	private String durataOre;
	private String durataMin;
	private String inizioOre;
	private String inizioMin;
	private ClassRoomType aula;
	private String corsoRiferimento;
	
	
	public Esame() {}
	
	public Esame(String emailDocente, Date data, String durataOre, String durataMin, String inizioOre, String inizioMin, ClassRoomType aula, Corso corso) {
		this.emailDocente = emailDocente;
		this.data = data;
		this.durataOre = durataOre;
		this.durataMin = durataMin;
		this.inizioOre = inizioOre;
		this.inizioMin = inizioMin;
		this.aula = aula;
		this.corsoRiferimento = corso.getNome();
	}
	//GETTER
	public String getDocente() {
		return emailDocente;
	}
	
	public Date getData() {
		return data;
	}
	
	public String[] getDurata() {
		String[] durata = {durataOre, durataMin};
		return durata;
	}
	public String[] getOraInizio() {
		String[] oraInizio = {inizioOre, inizioMin};
		return oraInizio;
	}
	
	public ClassRoomType getAula() {
		return aula;
	}
	
	public String getNomeCorsoRif() {
		return this.corsoRiferimento;
	}
	
	//SETTER
	
	public void setData(Date newData) {
		this.data = newData;
	}
	
	public void setDurata(String[] newDurata) {
		this.durataOre = newDurata[0];
		this.durataMin = newDurata[1];
	}
	
	public void setOraInizio(String[] newOraInizio) {
		this.inizioOre = newOraInizio[0];
		this.inizioMin = newOraInizio[1];
	}
	
	public void setAula(ClassRoomType newAula) {
		this.aula = newAula;
	}
}