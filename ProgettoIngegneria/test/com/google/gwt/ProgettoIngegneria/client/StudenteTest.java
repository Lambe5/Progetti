package com.google.gwt.ProgettoIngegneria.client;

import static org.junit.jupiter.api.Assertions.*;

import org.junit.jupiter.api.Test;

import com.google.gwt.core.client.GWT;
import com.google.gwt.junit.client.GWTTestCase;
import com.google.gwt.user.client.rpc.ServiceDefTarget;

public class StudenteTest extends GWTTestCase{
	
	
	Studente a = new Studente();
	Amministratore amm = new Amministratore();
	
	@Override
	public String getModuleName() {
		return "com.google.gwt.ProgettoIngegneria.progettoingegneria21_22";
	}
	
	public void test1GetCognome() {
		a.setCognome("rossi");
		assertEquals("rossi", a.getCognome());
	}
	
	public void test2GetNome() {
		a.setNome("andrea");
		assertEquals("andrea", a.getNome());
	}

	public void test3GetPassword() {
		a.setPassword("banana123");
		assertEquals("banana123", a.getPassword());
	}
	
	public void test4GetUtente() {
		assertEquals(TipoUtente.STUDENTE, a.getUtente());
	}
	
	public void test5GetEmail() {
		a.setEmail("marco.verdi@stud.uni.it");
		assertEquals("marco.verdi@stud.uni.it", a.getEmail());
	}
	
}
