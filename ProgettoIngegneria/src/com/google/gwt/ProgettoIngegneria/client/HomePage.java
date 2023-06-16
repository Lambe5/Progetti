package com.google.gwt.ProgettoIngegneria.client;

import com.google.gwt.user.client.Command;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.DockPanel;
import com.google.gwt.user.client.ui.FlexTable;
import com.google.gwt.user.client.ui.HorizontalPanel;
import com.google.gwt.user.client.ui.MenuBar;
import com.google.gwt.user.client.ui.MenuItem;

public class HomePage extends Composite {
	
	private DockPanel mainPanel;
	private HorizontalPanel centerPanel;
  	
	private String logPageString = "LogPage";
	private String homePageString = "HomePage";

	// creazione homepage
	public HomePage() {
		
		FlexTable ftHome= new FlexTable();
		mainPanel = new DockPanel();
		centerPanel = new HorizontalPanel();

		mainPanel.setSpacing(10);		// spazio tra le celle
		mainPanel.setBorderWidth(10);	// bordi
		
		centerPanel.setWidth("1080px");
		centerPanel.setHeight("400px");
		
		ftHome.setText(0,100,"HomePage dell'Univerista' Italiana per eccellenza");
		centerPanel.add(ftHome);
		createMenuBar();
		
		mainPanel.add(centerPanel, DockPanel.CENTER);
		
		initWidget(mainPanel);

	}
	
	private void createMenuBar() {
		
		MenuBar menu = new MenuBar();
		menu.setAutoOpen(true);
		Command cmdHomePage = new Command() {
			
			@Override
			public void execute() {
				ControllerPages.showThisPage(homePageString, "");
			}
		};
		//primo oggetto: Home
		menu.addItem("HOME", cmdHomePage);
		//secondo oggetto: menu universita'
		MenuBar uniMenu = new MenuBar(true);
		menu.addSeparator();
		MenuItem uniItem = new MenuItem("UNIVERSITA'", uniMenu);
		// popolo uniMenu
		uniMenu.addItem("Chi siamo", new Command() {

			@Override
			public void execute() {
				FlexTable testoChiSiamo= new FlexTable();
				testoChiSiamo.setText(0, 0, "UNIVERSITA' ITALIANA e' un'associazione senza scopo di lucro, "
						+ "e' apartitica e apolitica. "
						+ "E' inoltre indipendente da organizzazioni associative ed esercita la propria attivita' in piena autonomia. "
						+ "Essa ha per obiettivo la promozione della vita sociale, della cultura, dello sport, della fruizione costruttiva del tempo libero. "
						+ "Tali finalita' verranno perseguite dai Soci e dai collaboratori dell'Associazione attraverso specifiche iniziative.\r\n"
						+ "Tutti i soci maggiorenni godono, al momento dell'ammissione, del diritto di partecipazione nelle assemblee sociali, "
						+ "nonche' dell'elettorato attivo e passivo.");
				centerPanel.clear();
				centerPanel.add(testoChiSiamo);
			}
		});
		menu.addItem(uniItem);
		uniMenu.addItem("Dipartimenti", new Command() {

			@Override
			public void execute() {
				showDipartimenti();
			}
		});
		menu.addSeparator();
		menu.addItem("AIUTO", new Command() {
			
			@Override
			public void execute() {
				centerPanel.clear();
				FlexTable ftAiuto = new FlexTable();
				ftAiuto.setText(0,0,"Il Consorzio interuniversitario Universita' Italia "
						+ "contribuisce ad assicurare agli Organi di Governo degli Atenei aderenti, "
						+ "ai Nuclei di Valutazione, alle Commissioni impegnate nella Didattica e nell'Orientamento, "
						+ "attendibili e tempestive basi documentarie e di verifica, volte a favorire i processi decisionali "
						+ "e la programmazione delle attivita', con particolare riferimento a quelle di formazione e di servizio destinate al mondo studentesco."
						+ "Universita' Italia opera inoltre per agevolare e democratizzare l'accesso dei giovani al mercato del lavoro italiano ed internazionale.");
				
				ftAiuto.setText(1, 0, "Per avere maggior aiuto contattare la seguente email: aiutodesk@uni.it");
				centerPanel.add(ftAiuto);
			}
		});
		// oggetto: contatti
		menu.addSeparator();
		menu.addItem("CONTATTI", new Command() {

			@Override
			public void execute() {
				showContacts();
			}
		});
		// ultimo oggetto: log in
		menu.addSeparator();
		menu.addItem("IL MIO PROFILO", new Command() {

			@Override
			public void execute() {

				ControllerPages.showThisPage(logPageString, " ");
			}
		});
		mainPanel.add(menu, DockPanel.NORTH);
	}
  
	private void showDipartimenti() {
		centerPanel.clear();
		FlexTable dipartimentiPanel = new FlexTable();
		dipartimentiPanel.setText(0, 0, "I dipartimenti sono le articolazioni organizzative dell'Universita'  per lo svolgimento delle funzioni relative alla ricerca scientifica e alle attivita' didattiche formative");

		String[] dipartimenti = {"Architettura", "Beni culturali", "Chimica", "Farmacia e Biotecnologie",
				"Filologia classica e italianistica", "Filosofia e Comunicazione", "Fisica e Astronomia",
				"Informatica", "Informatica per il management", "Ingegneria meccanica", "Matematica",
				"Mediazione Linguistica Interculturale", "Medicina", "Psicologia", "Scienze aziendali", 
				"Scienze economiche", "Storia e Cultura"};

		for(int i = 0; i < dipartimenti.length; i++) {
			dipartimentiPanel.setText(i+10, 0, dipartimenti[i]);
		}
		centerPanel.add(dipartimentiPanel);
	}
  
	private void showContacts() {
		centerPanel.clear();
		FlexTable contactsTable = new FlexTable();
		contactsTable.setText(0, 0, "Contatti:");
		contactsTable.setText(1, 0, "Telefono: 333 1234567");
		contactsTable.setText(2, 0, "Email: universita.segreteria@seg.uni.it"); 
		contactsTable.setText(3, 0, "Indirizzo: Via Pallino Pinco, 01");
		
		centerPanel.add(contactsTable);

	}
}
