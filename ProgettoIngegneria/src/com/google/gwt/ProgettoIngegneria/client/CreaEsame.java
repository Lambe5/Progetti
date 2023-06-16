package com.google.gwt.ProgettoIngegneria.client;
import java.util.Date;

public class CreaEsame {
	//ha la responsabilita' di creare un esame
	protected Esame esame;
	public Esame creaEsame(String emailDocente, Date data, String durataOre, String durataMin, String inizioOre, String inizioMin, ClassRoomType aula, Corso corso) {
		
		if( data == null )
			return null;
		
		esame = new Esame(emailDocente,data,durataOre,durataMin,inizioOre,inizioMin,aula,corso);
		return esame;
	}
}
