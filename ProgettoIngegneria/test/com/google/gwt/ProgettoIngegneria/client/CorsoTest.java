package com.google.gwt.ProgettoIngegneria.client;

import static org.junit.jupiter.api.Assertions.*;

import org.junit.Assert;
import org.junit.jupiter.api.Test;

import com.google.gwt.core.client.GWT;
import com.google.gwt.junit.client.GWTTestCase;
import com.google.gwt.user.client.rpc.ServiceDefTarget;

public class CorsoTest extends GWTTestCase{

	Corso corso = new Corso();
	
	@Override
	public String getModuleName() {
		return "com.google.gwt.ProgettoIngegneria.progettoingegneria21_22";
	}
	
	
	public void test1CreaCorso() {
		CreaCorso newCorso = new CreaCorso();
		assertEquals(null, newCorso.creaCorso(null, null, null, null, null,null));
	}
	
	public void test2GetNome() {
		corso.setNome("corso1");
		assertEquals("corso1", corso.getNome());
	}
	
	public void test3GetEmailCodocente() {
		corso.setEmailCodocente("andrea.bianchi@doc.uni.it");
		assertEquals("andrea.bianchi@doc.uni.it", corso.getEmailCodocente());
	}
	
	public void test4GetDescrizione() {
		corso.setDescrizione("questo corso è bello");
		assertEquals("questo corso è bello", corso.getDescrizione());
	}
	
	public void test5GetInitDate() {
		corso.setInitDate(null);
		assertEquals(null, corso.getInitDate());
	}
	
	public void test6GetEndDate() {
		corso.setEndDate(null);;
		assertEquals(null, corso.getEndDate());
	}
	
	
}
