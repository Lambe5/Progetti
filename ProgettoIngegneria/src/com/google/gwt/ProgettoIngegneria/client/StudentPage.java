package com.google.gwt.ProgettoIngegneria.client;
import java.util.ArrayList;
import java.util.Date;
import com.google.gwt.cell.client.ButtonCell;
import com.google.gwt.cell.client.DateCell;
import com.google.gwt.cell.client.FieldUpdater;
import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.i18n.client.DateTimeFormat;
import com.google.gwt.user.cellview.client.CellTable;
import com.google.gwt.user.cellview.client.Column;
import com.google.gwt.user.cellview.client.TextColumn;
import com.google.gwt.user.client.Command;
import com.google.gwt.user.client.Window;
import com.google.gwt.user.client.rpc.AsyncCallback;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.DockPanel;
import com.google.gwt.user.client.ui.FlexTable;
import com.google.gwt.user.client.ui.MenuBar;
import com.google.gwt.user.client.ui.MenuItem;
import com.google.gwt.user.client.ui.TextBox;
import com.google.gwt.user.client.ui.VerticalPanel;
import com.google.gwt.view.client.ListDataProvider;


public class StudentPage extends Composite {
	
	DockPanel         mainPanel;
	VerticalPanel   centerPanel;
	
	String emailAccount;
	String homePageString = "HomePage";

	DateTimeFormat defaultDateFormat = DateTimeFormat.getFormat("dd/MM/yyyy");
	
	final GreetingServiceAsync gs = GWT.create(GreetingService.class);

	
	public StudentPage(String emailAccount) {

		this.emailAccount = emailAccount;

		mainPanel = new DockPanel();
		centerPanel = new VerticalPanel();

		mainPanel.setSpacing(10);
		centerPanel.setWidth("1000px");

		showWelcomePanel();
		showMenuPanel();
		mainPanel.add(centerPanel, DockPanel.CENTER);

		initWidget(mainPanel);
	}
	
	private void showWelcomePanel() {
		FlexTable tcWelcome = new FlexTable();
		gs.getInfo(emailAccount, new AsyncCallback<Account>() {

			@Override
			public void onFailure(Throwable caught) {}

			@Override
			public void onSuccess(Account account) {
				tcWelcome.setText(0, 0, "Benvenuto/a " + account.getNome() + " " + account.getCognome());
			}
		});
		
		Button logOutButton = new Button("Log Out");
		logOutButton.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				ControllerPages.showThisPage(homePageString, "");
			}
		});
		tcWelcome.setWidget(1, 0, logOutButton);
		
		mainPanel.add(tcWelcome, DockPanel.NORTH);
	}
	
	private void showMenuPanel() {
		
		MenuBar menuPanel = new MenuBar(true);

		menuPanel.setAutoOpen(true);
		
		menuPanel.addItem("Profilo", new Command() {
			
			@Override
			public void execute() {
				showProfilePanel();
			}
		});
		menuPanel.addSeparator();
		
		MenuBar corsiMenu = new MenuBar(true);
		MenuItem corsiItem = new MenuItem("Corsi", corsiMenu);
		
		// popolo menu corsi
		corsiMenu.addItem("Tutti i corsi", new Command() {

			@Override
			public void execute() {
				showCorsi(false);
			}
		});
		corsiMenu.addItem("Miei corsi", new Command() {

			@Override
			public void execute() {
				showCorsi(true);
			}
		});
		menuPanel.addItem(corsiItem);
		
		menuPanel.addSeparator();
		menuPanel.addItem("Esami", new Command() {
			
			@Override
			public void execute() {
				showEsami();
			}
		});
		menuPanel.addSeparator();
		menuPanel.addItem("Voti", new Command() {
			
			@Override
			public void execute() {
				showVoti();
			}
		});
		
		mainPanel.add(menuPanel, DockPanel.WEST);
	}
	
	private void showProfilePanel() {
		centerPanel.clear();
		FlexTable profilePanel = new FlexTable();
		Button btnEdit = new Button("Modifica password");
		btnEdit.addClickHandler(new ClickHandler() {
			
			@Override
			public void onClick(ClickEvent event) {
				showEditingPanel(profilePanel, btnEdit);
			}
		});
		
		gs.getInfo(emailAccount, new AsyncCallback<Account>() {

			@Override
			public void onFailure(Throwable caught) {
				Window.alert("Errore connessione server");
			}

			@Override
			public void onSuccess(Account account) {
				//mostro le info personali 
				profilePanel.setText(0, 0, "Nome: ");
				profilePanel.setText(0, 1, account.getNome());
				profilePanel.setText(1, 0, "Cognome: " );
				profilePanel.setText(1, 1, account.getCognome());
				profilePanel.setText(2, 0, "Email: ");
				profilePanel.setText(2, 1, account.getEmail());
				profilePanel.setText(3, 0, "Password: ");
				profilePanel.setText(3, 1, account.getPassword());
				profilePanel.setWidget(4, 0, btnEdit);
			}
		});
		centerPanel.add(profilePanel);
	}
	
	private void showEditingPanel(FlexTable profilePanel, Button btnEdit) {
		
		TextBox tbPassword = new TextBox();
		Button btnSaveChanges = new Button("Salva modifiche");
		btnSaveChanges.addClickHandler(new ClickHandler() {
			
			@Override
			public void onClick(ClickEvent event) {
				if(tbPassword.getText() == null || tbPassword.getText() == "" ) {
					Window.alert("La password non puo' essere vuota");
					return;
				}
					
				gs.getInfo(emailAccount, new AsyncCallback<Account>() {

					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Errore connessione server");
					}

					@Override
					public void onSuccess(Account account) {
						account.setPassword(tbPassword.getText());
						gs.editAccount(account, new AsyncCallback<Void>() {

							@Override
							public void onFailure(Throwable caught) {
								Window.alert("Errore connessione server");
							}

							@Override
							public void onSuccess(Void result) {
								// torna a mostrare profilePanel originale
								profilePanel.setText(3, 1, account.getPassword());
								profilePanel.setWidget(4, 0, btnEdit);
							}
						});
					}
				});
			}
		});
		profilePanel.setWidget(3, 1, tbPassword);
		profilePanel.setWidget(4, 0, btnSaveChanges);
	}
	
	// Mostra tutti i corsi 
	private void showCorsi(boolean myCorsi) {
		
		centerPanel.clear();
		ListDataProvider<Corso> dataProvider = new ListDataProvider<>();
		
		CellTable<Corso> ctCorsi = new CellTable<Corso>();
		dataProvider.addDataDisplay(ctCorsi);
		
		TextColumn<Corso> nameClassColumn = new TextColumn<Corso>() {

			@Override
			public String getValue(Corso corso) {
				return corso.getNome();
			}
		};
		TextColumn<Corso> descriptionClassColumn = new TextColumn<Corso>() {

			@Override
			public String getValue(Corso corso) {
				return corso.getDescrizione();
			}
		};
		TextColumn<Corso> professorColumn = new TextColumn<Corso>() {

			@Override
			public String getValue(Corso corso) {
				return corso.getEmailDocente();
			}
		};
		TextColumn<Corso> coprofessorColumn = new TextColumn<Corso>() {

			@Override
			public String getValue(Corso corso) {
				
				return corso.getEmailCodocente() != null ? corso.getEmailCodocente() : null;
			}
		};
		
		ButtonCell buttonCorsoCell = new ButtonCell();
		Column<Corso, String> subscribtionColumn = new Column<Corso, String>(buttonCorsoCell) {

			@Override
			public String getValue(Corso corso) {
				if(myCorsi) {
					this.setFieldUpdater(new FieldUpdater<Corso, String>() {

						@Override
						public void update(int index, Corso corso, String value) {
							gs.disiscrizioneCorso(corso.getNome(), emailAccount, new AsyncCallback<Void>() {

								@Override
								public void onFailure(Throwable caught) {
									Window.alert("Errore connessione server");
								}

								@Override
								public void onSuccess(Void result) {
									showCorsi(myCorsi);
								}
							});
						}
					});
					return "Disiscriviti";
					
				} else {
					this.setFieldUpdater(new FieldUpdater<Corso, String>() {

						@Override
						public void update(int index, Corso corso, String value) {
							
							gs.iscrizioneCorso(corso.getNome(), emailAccount, new AsyncCallback<Void>() {
								@Override
								public void onFailure(Throwable caught) {
									Window.alert("Errore connessione server");
								}

								@Override
								public void onSuccess(Void result) {
									showCorsi(myCorsi);
								}
							});
						}
					});
					return "Iscriviti";
				}
			}
		};
		
		ButtonCell buttonExamCell = new ButtonCell();
		Column<Corso, String> examColumn = new Column<Corso, String>(buttonExamCell) {

			@Override
			public String getValue(Corso corso) {
				this.setFieldUpdater(new FieldUpdater<Corso, String>() {
					@Override
					public void update(int index, Corso corso, String value) {

						showExamPanel(corso);
					}
				});
				return "Visualizza esame";
			}
		};
		
		ctCorsi.addColumn(nameClassColumn, "Nome");
		ctCorsi.addColumn(descriptionClassColumn, "Descrizione");
		ctCorsi.addColumn(professorColumn, "Docente");
		ctCorsi.addColumn(coprofessorColumn, "Codocente");
		ctCorsi.addColumn(subscribtionColumn);
		
		// mostra la colonna solo se e' iscritto
		if(myCorsi) {
			ctCorsi.addColumn(examColumn, "Esame");
		}
		
		// tutti i corsi per iscriversi
		gs.getAllCorsi(new AsyncCallback<ArrayList<Corso>>() {

			@Override
			public void onFailure(Throwable caught) {
				Window.alert("Errore connessione server");
			}

			@Override
			public void onSuccess(ArrayList<Corso> allCorsi) {
				
				// da qui si divide e mostra i miei corsi o gli altri
				gs.getMyCorsi(emailAccount, new AsyncCallback<ArrayList<String>>() {

					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Errore connessione Server");
					}

					@Override
					public void onSuccess(ArrayList<String> listaNomiCorsi) {
						ArrayList<Corso> listaCorsi = new ArrayList<>();
						
						for(Corso corso : allCorsi) {
							if(myCorsi) {
								// creo lista con corsi a cui e' iscritto
								// se non e' iscritto a niente esce subito
								if(listaNomiCorsi == null) {
									break;
								}
								if(listaNomiCorsi.contains(corso.getNome())) {
									listaCorsi.add(corso);
								}
							} else {
								// creo lista con corsi a cui non e' iscritto
								if(listaNomiCorsi == null || !listaNomiCorsi.contains(corso.getNome())) {
									listaCorsi.add(corso);
								}
							}
						}
						dataProvider.getList().clear();
						dataProvider.getList().addAll(listaCorsi);
					}
				});
			}
		});
		centerPanel.add(ctCorsi);
	}
	
	private void showEsami() {
		
		centerPanel.clear();
		
		ListDataProvider<Esame> dataProvider = new ListDataProvider<>();
		
		CellTable<Esame>  examTable = new CellTable<Esame>();

		dataProvider.addDataDisplay(examTable);
		
		TextColumn<Esame> nameExamColumn = new TextColumn<Esame>() {

			@Override
			public String getValue(Esame esame) {
				return esame.getNomeCorsoRif();
			}
		};
		DateCell dateCell = new DateCell();
		Column<Esame, Date> dateColumn = new Column<Esame, Date>(dateCell) {

			@Override
			public Date getValue(Esame esame) {
				return esame.getData();
			}
		};
		TextColumn<Esame> durataColumn = new TextColumn<Esame>() {
			
			@Override
			public String getValue(Esame esame) {
				return esame.getDurata()[0] + ":" + esame.getDurata()[1];
			}
		};
		TextColumn<Esame> aulaColumn = new TextColumn<Esame>() {
			
			@Override
			public String getValue(Esame esame) {
				return esame.getAula().toString();
			}
		};
	
		ButtonCell buttonEsameCell = new ButtonCell();
		Column<Esame, String> unsubscribtionColumn = new Column<Esame, String>(buttonEsameCell) {

			@Override
			public String getValue(Esame esame) {
				this.setFieldUpdater(new FieldUpdater<Esame, String>() {

					@Override
					public void update(int index, Esame esame, String value) {
						gs.disiscrizioneEsame(esame.getNomeCorsoRif(), emailAccount, new AsyncCallback<Void>() {

							@Override
							public void onFailure(Throwable caught) {
								Window.alert("Errore connessione server");
							}

							@Override
							public void onSuccess(Void result) {
								showEsami();
							}
						});
					}
				});
				return "Disiscriviti";
			}
		};
		
		examTable.addColumn(nameExamColumn, "Nome");
		examTable.addColumn(dateColumn, "Data");
		examTable.addColumn(durataColumn, "Durata (ore)");
		examTable.addColumn(aulaColumn, "Aula");
		examTable.addColumn(unsubscribtionColumn);
		
		// ottengo i corsi a cui e' iscritto lo studente cosi' mostro solo gli esami di quei corsi
		gs.getInfo(emailAccount, new AsyncCallback<Account>() {

			@Override
			public void onFailure(Throwable caught) {
				Window.alert("Errore connessione server");
			}

			@Override
			public void onSuccess(Account account) {
				gs.getAllEsami(new AsyncCallback<ArrayList<Esame>>() {

					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Errore connessione server");
					}

					@Override
					public void onSuccess(ArrayList<Esame> allEsami) {
						gs.getMyEsami(emailAccount, new AsyncCallback<ArrayList<String>>() {

							@Override
							public void onFailure(Throwable caught) {
								Window.alert("Errore connessione server");
							}

							@Override
							public void onSuccess(ArrayList<String> listaNomiEsami) {
								ArrayList<Esame> listaEsami = new ArrayList<>();

								// creo lista con esami a cui e' iscritto
								for(Esame esame : allEsami) {

									// se non e' iscritto a niente esce subito
									if(listaNomiEsami == null) {
										break;
									}
									if(listaNomiEsami.contains(esame.getNomeCorsoRif())) {
										listaEsami.add(esame);
									}
								}
								dataProvider.getList().clear();
								dataProvider.getList().addAll(listaEsami);
							}
						});
					}
				});
			}
		});
		centerPanel.add(examTable);
	}
	
	private void showExamPanel(Corso corso) {
		
		centerPanel.clear();
		FlexTable examPanel = new FlexTable();
		Button btnBack = new Button("Indietro");
		btnBack.addClickHandler(new ClickHandler() {
			
			@Override
			public void onClick(ClickEvent event) {
				showCorsi(true);
			}
		});
		
		gs.getEsame(corso, new AsyncCallback<Esame>() {

			@Override
			public void onFailure(Throwable caught) {
				Window.alert("Errore connessione server");
			}

			@Override
			public void onSuccess(Esame esame) {
				if(esame == null) {
					// se non esiste esame
					
					examPanel.setText(0, 0, "Non ci sono esami per questo corso");
					examPanel.setWidget(1, 0, btnBack);
					
				} else {
					// se esiste esame
					Button btnIscriviti    = new Button("Iscriviti");
					Button btnDisiscriviti = new Button("Disiscriviti");
					btnIscriviti.addClickHandler(new ClickHandler() {
						
						@Override
						public void onClick(ClickEvent event) {
							gs.iscrizioneEsame(esame.getNomeCorsoRif(), emailAccount, new AsyncCallback<Void>() {

								@Override
								public void onFailure(Throwable caught) {
									Window.alert("Errore connessione server");
								}

								@Override
								public void onSuccess(Void result) {
									showEsami();
								}
							});
						}
					});

					btnDisiscriviti.addClickHandler(new ClickHandler() {

						@Override
						public void onClick(ClickEvent event) {
							gs.disiscrizioneEsame(esame.getNomeCorsoRif(), emailAccount, new AsyncCallback<Void>() {

								@Override
								public void onFailure(Throwable caught) {
									Window.alert("Errore connessione server");
								}

								@Override
								public void onSuccess(Void result) {
									showEsami();
								}
							});
						}
					});
					
					gs.getMyEsami(emailAccount, new AsyncCallback<ArrayList<String>>() {

						@Override
						public void onFailure(Throwable caught) {
							Window.alert("Errore connessione server");
						}

						@Override
						public void onSuccess(ArrayList<String> listaNomiEsami) {
							examPanel.setText(0, 0, "Corso: ");
							examPanel.setText(0, 1, esame.getNomeCorsoRif());
							examPanel.setText(1, 0, "Data: ");
							examPanel.setText(1, 1, defaultDateFormat.format(esame.getData()).toString());
							examPanel.setText(2, 0, "Ora: ");
							examPanel.setText(2, 1, esame.getOraInizio()[0] + ":" + esame.getOraInizio()[1]);
							examPanel.setText(3, 0, "Durata: ");
							examPanel.setText(3, 1, esame.getDurata()[0] + ":" + esame.getDurata()[1] + " ore");
							examPanel.setText(4, 0, "Aula: ");
							examPanel.setText(4, 1, esame.getAula().toString());
							examPanel.setWidget(5, 0, btnBack);
							if(!listaNomiEsami.contains(esame.getNomeCorsoRif())) {
								examPanel.setWidget(5, 1, btnIscriviti);
							} else {
								examPanel.setWidget(5, 1, btnDisiscriviti);
							}
						}
					});
				}
			}
		});
		centerPanel.add(examPanel);
	}
	
	private void showVoti() {
		
		centerPanel.clear();
		
		ListDataProvider<Voto> dataProvider = new ListDataProvider<>();
		
		CellTable<Voto>  votoTable = new CellTable<Voto>();

		dataProvider.addDataDisplay(votoTable);
		
		TextColumn<Voto> nameCorsoColumn = new TextColumn<Voto>() {

			@Override
			public String getValue(Voto voto) {
				return voto.getCorso();
			}
		};
		
		TextColumn<Voto> votoColumn = new TextColumn<Voto>() {

			@Override
			public String getValue(Voto voto) {
				return String.valueOf(voto.getVoto());
			}
		};
		
		votoTable.addColumn(nameCorsoColumn, "Corso");
		votoTable.addColumn(votoColumn, "Voto");
		
		//getVoti
		gs.getGradesFromStudEmail(emailAccount, new AsyncCallback<ArrayList<Voto>>() {

			@Override
			public void onFailure(Throwable caught) {
			}

			@Override
			public void onSuccess(ArrayList<Voto> listaVoti) {
				// disegno la tabella
				
				dataProvider.getList().clear();
				dataProvider.getList().addAll(listaVoti);
			}
		});
		
		centerPanel.add(votoTable);
	}
}
