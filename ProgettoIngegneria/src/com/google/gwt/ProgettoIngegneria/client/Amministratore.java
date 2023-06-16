package com.google.gwt.ProgettoIngegneria.client;
import java.io.Serializable;
import java.util.Random;

public class Amministratore extends Account implements Serializable{
	
	protected final String email;
	protected final String password;
	protected Account account;
	protected FactoryCreaUtente factory = new FactoryCreaUtente();
	
	//L'array viene usato per creare una password random iniziale per ogni Account
	char[] elePw = {'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
			        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
			        '1','2','3','4','5','6','7','8','9','0'};
	char[] pwUtente = new char [8];

	public Amministratore() {
		super(TipoUtente.AMMINISTRATORE);
		this.email = "admin";
		this.password = "admin";
	}
	
	@Override
	public TipoUtente getUtente() {
		return this.utente;
	}
	
	//Admin crea l'account e gli inserisce una password random iniziale
	public Account creaUtente(TipoUtente t, String email, String nome, String cognome) {

		Random random = new Random();
		int index = 0;
		for(int i=0; i<8; i++) {
			index = random.nextInt(62);
			pwUtente[i] = elePw[index];
		}
		String rndPassword = new String(pwUtente);
		this.account = factory.creaUtente(t, email, rndPassword, nome, cognome);
		if(account != null && email != "" && nome != "" && cognome != "")
			return account;
		else return null;
	}
}
