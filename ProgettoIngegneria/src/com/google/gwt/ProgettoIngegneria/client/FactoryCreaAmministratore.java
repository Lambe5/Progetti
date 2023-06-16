package com.google.gwt.ProgettoIngegneria.client;

public class FactoryCreaAmministratore {
	protected Amministratore amm;

	public Amministratore creaAdmin() {
		amm = new Amministratore();
		return amm;

		
	}
}	
