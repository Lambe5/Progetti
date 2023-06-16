package com.google.gwt.ProgettoIngegneria.client;

import static org.junit.jupiter.api.Assertions.*;

import org.junit.Assert;
import org.junit.jupiter.api.Test;

import com.google.gwt.core.client.GWT;
import com.google.gwt.junit.client.GWTTestCase;
import com.google.gwt.user.client.rpc.ServiceDefTarget;

public class EsameTest extends GWTTestCase{

	Esame corso = new Esame();
	
	@Override
	public String getModuleName() {
		return "com.google.gwt.ProgettoIngegneria.progettoingegneria21_22";
	}
	
	
	public void test1CreaCorso() {
		CreaEsame newCorso = new CreaEsame();
		assertEquals(null, newCorso.creaEsame(null,null, null, null, null,null,null,null));
	}
	
	public void test2GetInitDate() {
		corso.setAula(ClassRoomType.AULA1);
		assertEquals(ClassRoomType.AULA1, corso.getAula());
	}
	
	public void test3GetEndDate() {
		corso.setData(null);
		assertEquals(null, corso.getData());
	}
}
