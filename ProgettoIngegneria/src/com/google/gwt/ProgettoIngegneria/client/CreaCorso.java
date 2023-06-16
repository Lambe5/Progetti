package com.google.gwt.ProgettoIngegneria.client;

import java.util.Date;

public class CreaCorso {
	Corso corso;
	public Corso creaCorso(String nome, Date initDate, Date endDate, String descrizione, String docente,String codocente) {
		
		if(nome == null && initDate == null && endDate == null )
			return null;
		
		corso = new Corso(nome,initDate,endDate,descrizione,docente, codocente);
		return corso;
	}
}
