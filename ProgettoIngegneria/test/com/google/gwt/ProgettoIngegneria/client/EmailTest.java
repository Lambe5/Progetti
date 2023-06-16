package com.google.gwt.ProgettoIngegneria.client;

import static org.junit.jupiter.api.Assertions.*;

import org.junit.jupiter.api.Test;

import com.google.gwt.core.client.GWT;
import com.google.gwt.junit.client.GWTTestCase;
import com.google.gwt.user.client.rpc.ServiceDefTarget;

public class EmailTest extends GWTTestCase{
	
	@Override
	public String getModuleName() {
		return "com.google.gwt.ProgettoIngegneria.progettoingegneria21_22";
	}
	
	
	public void test1NullInput() {
		
		AdminPage adminPage = new AdminPage();
		
		assertEquals("Input non corretto", adminPage.createEmail(null, null, null));
	}

	public void test2NullInput() {
		
		AdminPage adminPage = new AdminPage();
		assertEquals("Input non corretto", adminPage.createEmail("", null, null));
	}	
	
	public void test3NullInput() {
		
		AdminPage adminPage = new AdminPage();
		assertEquals("Input non corretto", adminPage.createEmail(null, "", null));
	}	
	
	public void test4NullInput() {
		
		AdminPage adminPage = new AdminPage();
		assertEquals("Input non corretto", adminPage.createEmail(null, null, ""));
	}	
	
	public void test5NullInput() {
		
		AdminPage adminPage = new AdminPage();
		assertEquals("Input non corretto", adminPage.createEmail("", "", ""));
	}	
	
	
	public void test6NullInput() {
		AdminPage adminPage = new AdminPage();
		assertEquals("Input non corretto", adminPage.createEmail("", "", null));
	}
	
	public void test7NullInput() {
		AdminPage adminPage = new AdminPage();
		assertEquals("Input non corretto", adminPage.createEmail(null, "", ""));
	}
	
	public void test8NullInput() {
		AdminPage adminPage = new AdminPage();
		assertEquals("Input non corretto", adminPage.createEmail("", null, ""));
	}
	
	public void test1NumberInput() {
		AdminPage adminPage = new AdminPage();
		assertEquals("Input non corretto", adminPage.createEmail("4è!", "t", "studente"));
	}
	

	
	

}
