package com.google.gwt.ProgettoIngegneria.client;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Comparator;
import java.util.Date;

import com.google.gwt.cell.client.ActionCell;
import com.google.gwt.cell.client.ActionCell.Delegate;
import com.google.gwt.cell.client.ClickableTextCell;
import com.google.gwt.cell.client.DateCell;
import com.google.gwt.cell.client.FieldUpdater;
import com.google.gwt.cell.client.SelectionCell;
import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ChangeEvent;
import com.google.gwt.event.dom.client.ChangeHandler;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.event.dom.client.KeyUpEvent;
import com.google.gwt.event.dom.client.KeyUpHandler;
import com.google.gwt.event.logical.shared.ValueChangeEvent;
import com.google.gwt.event.logical.shared.ValueChangeHandler;
import com.google.gwt.i18n.client.DateTimeFormat;
import com.google.gwt.user.cellview.client.CellTable;
import com.google.gwt.user.cellview.client.Column;
import com.google.gwt.user.cellview.client.SimplePager;
import com.google.gwt.user.cellview.client.TextColumn;
import com.google.gwt.user.cellview.client.ColumnSortEvent.ListHandler;
import com.google.gwt.user.cellview.client.SimplePager.TextLocation;
import com.google.gwt.user.client.Command;
import com.google.gwt.user.client.Window;
import com.google.gwt.user.client.rpc.AsyncCallback;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.DockPanel;
import com.google.gwt.user.client.ui.FlexTable;
import com.google.gwt.user.client.ui.HorizontalPanel;
import com.google.gwt.user.client.ui.ListBox;
import com.google.gwt.user.client.ui.MenuBar;
import com.google.gwt.user.client.ui.MenuItem;
import com.google.gwt.user.client.ui.MultiWordSuggestOracle;
import com.google.gwt.user.client.ui.SuggestBox;
import com.google.gwt.user.client.ui.TextArea;
import com.google.gwt.user.client.ui.TextBox;
import com.google.gwt.user.datepicker.client.DateBox;
import com.google.gwt.view.client.ListDataProvider;
import com.google.gwt.view.client.ProvidesKey;

public class ProfessorPage extends Composite {

	DockPanel mainPanel;
	HorizontalPanel centerPanel;

	String emailAccount;

	DateTimeFormat defaultDateFormat = DateTimeFormat.getFormat("dd/MM/yyyy");

	final GreetingServiceAsync gs = GWT.create(GreetingService.class);
	final private String homePageString="HomePage";
	
	public ProfessorPage(String emailAccount) {

		this.emailAccount = emailAccount;

		mainPanel = new DockPanel();
		centerPanel = new HorizontalPanel();

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

		Button btnLogOut = new Button("Log Out");
		btnLogOut.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				ControllerPages.showThisPage(homePageString, "");
			}
		});

		tcWelcome.setWidget(1, 0, btnLogOut);
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
		corsiMenu.setAutoOpen(true);
		MenuItem corsiItem = new MenuItem("Corsi", corsiMenu);

		corsiMenu.addItem("Crea nuovo corso", new Command() {

			@Override
			public void execute() {
				showCreateCorsiPanel();
			}
		});

		corsiMenu.addItem("Cerca corso", new Command() {

			@Override
			public void execute() {
				showFindCorsiPanel();
			}
		});

		menuPanel.addItem(corsiItem);
		mainPanel.add(menuPanel, DockPanel.WEST);
	}

	private void showProfilePanel() {

		centerPanel.clear();
		FlexTable profilePanel = new FlexTable();
		Button btnEdit = new Button("Modifica password");
		btnEdit.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				showEditProfilePanel(profilePanel, btnEdit);
			}
		});

		gs.getInfo(emailAccount, new AsyncCallback<Account>() {

			@Override
			public void onFailure(Throwable caught) {
				Window.alert("Errore connessione server");
			}

			@Override
			public void onSuccess(Account account) {
				if(account == null) {
					Window.alert("nullo");
				}
				//mostro le info personali (mancano ancora i corsi) nella pagina
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

	private void showEditProfilePanel(FlexTable profilePanel, Button btnEdit) {

		TextBox    tbPassword = new TextBox();
		Button btnSaveChanges = new Button("Conferma modifiche");
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
	private void showCreateCorsiPanel() {

		centerPanel.clear();
		
		FlexTable  createPanel = new FlexTable();
		TextBox 	    tbNome = new TextBox();
		DateBox        dbStart = new DateBox();
		DateBox   	 	 dbEnd = new DateBox();
		TextArea taDescription = new TextArea();
		Button 		 btnCreate = new Button("Crea Corso");

		//Setto il formato delle date giorno/mese/anno
		dbStart.setFormat(new DateBox.DefaultFormat(defaultDateFormat));
		dbEnd.setFormat(new DateBox.DefaultFormat(defaultDateFormat));

		MultiWordSuggestOracle oracle = new MultiWordSuggestOracle();

		gs.getProfessors(new AsyncCallback<ArrayList<Account>>() {

			@Override
			public void onFailure(Throwable caught) {
				Window.alert("Errore connessione server");
			}

			@Override
			public void onSuccess(ArrayList<Account> listaDocenti) {

				for(Account docente : listaDocenti) {
					oracle.add(docente.getNome() + " " + docente.getCognome()
					+ " (" + docente.getEmail() + ")");
				}
			}
		});

		SuggestBox sbProfessor = new SuggestBox(oracle);

		createPanel.setText(  0, 0, "Nome");
		createPanel.setWidget(0, 1, tbNome);
		createPanel.setText(  1, 0, "Data inizio: ");
		createPanel.setWidget(1, 1, dbStart);
		createPanel.setText(  2, 0, "Data fine: ");
		createPanel.setWidget(2, 1, dbEnd);
		createPanel.setText(3, 0, "Codocente: ");
		createPanel.setWidget(3, 1, sbProfessor);
		createPanel.setText(  4, 0, "Descrizione");
		createPanel.setWidget(4, 1, taDescription);
		createPanel.setWidget(5, 0, btnCreate);

		btnCreate.addClickHandler(new ClickHandler() {

			String emailCodocente = null;
			CreaCorso factoryCreaCorso= new CreaCorso();
			
			@Override
			public void onClick(ClickEvent event) {

				// data fine deve essere dopo data inizio
				if(!dbEnd.getValue().after(dbStart.getValue())) {
					createPanel.setText(6, 1, "La data di fine corso deve essere successiva alla data di inizio");
					return;
				} else {
					createPanel.setText(6, 1, "");
				}

				if(!sbProfessor.getText().equals("")) {
					// se non sceglie dai suggeriti potrebbe non esistere

					String infoCodocente = sbProfessor.getText();
					int indexBracket = -1;

					for(int i = 0; i < infoCodocente.length(); i++) {
						if(infoCodocente.charAt(i) == '(') {
							indexBracket = i+1;
							break;
						}
					}	

					emailCodocente = sbProfessor.getText().substring(indexBracket, infoCodocente.length()-1);
				}
				Corso corso = factoryCreaCorso.creaCorso(
						tbNome.getText(), dbStart.getValue(), dbEnd.getValue(), 
						taDescription.getText(), emailAccount, emailCodocente);

				gs.createCorso(corso, new AsyncCallback<Void>() {

					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Errore connessione server");
					}

					@Override
					public void onSuccess(Void result) {
						gs.getCorso(tbNome.getText(), new AsyncCallback<Corso>() {

							@Override
							public void onFailure(Throwable caught) {
								Window.alert("Errore connessione server9");
							}

							@Override
							public void onSuccess(Corso corso) {

								createPanel.setText(6,0,"Riepilogo informazioni:");
								createPanel.setText(7,1,"Nome: "		 + corso.getNome());
								createPanel.setText(8,1,"Inizio: "		 + defaultDateFormat.format(corso.getInitDate()));
								createPanel.setText(9,1,"Fine: "		 + defaultDateFormat.format(corso.getEndDate()));
								createPanel.setText(10,1,"Descrizione: " + corso.getDescrizione());

								if(corso.getEmailCodocente() != null) {
									gs.getInfo(corso.getEmailCodocente(), new AsyncCallback<Account>() {

										@Override
										public void onFailure(Throwable caught) {
											Window.alert("Errore connessione Server");

										}

										@Override
										public void onSuccess(Account codocente) {
											createPanel.setText(11,1,"Codocente: "   + codocente.getNome() + " " + codocente.getCognome());
										}
									});
								} else {
									createPanel.setText(11, 1, "Codocente: ");
								}
							}
						});
					}
				});
			}
		});
		centerPanel.add(createPanel);
	}

	private void showFindCorsiPanel() {

		centerPanel.clear();

		DockPanel tablePanel = new DockPanel();

		ListDataProvider<Corso> dataProvider = new ListDataProvider<>();

		ListHandler<Corso> columnSortHandler = new ListHandler<>(dataProvider.getList());

		ProvidesKey<Corso> keyProvider = new ProvidesKey<Corso>() {

			@Override
			public Object getKey(Corso item) {
				return (item == null) ? null : item.id;
			}
		};

		// Create a CellTable with a key provider.
		final CellTable<Corso> ctCorsi = new CellTable<Corso>(keyProvider);

		// pager per scorrere pagine tabella
		SimplePager.Resources pagerResources = GWT.create(SimplePager.Resources.class);
		SimplePager pager = new SimplePager(TextLocation.CENTER, pagerResources, false, 0, true);
		pager.setDisplay(ctCorsi);

		dataProvider.addDataDisplay(ctCorsi);

		// Colonna nome
		TextColumn<Corso> nameColumn = new TextColumn<Corso>() {

			@Override
			public String getValue(Corso corso) {
				return corso.getNome();
			}
		};
		// Colonna descrizione
		TextColumn<Corso> descColumn = new TextColumn<Corso>() {
			@Override
			public String getValue(Corso corso) {
				return corso.getDescrizione();
			}
		};
		//Colonna data inizio
		DateCell startDateCell = new DateCell(defaultDateFormat);
		Column<Corso, Date> startColumn = new Column<Corso, Date>(startDateCell) {

			@Override
			public Date getValue(Corso corso) {
				return corso.getInitDate();
			}
		};
		// Colonna data fine
		DateCell endDateCell = new DateCell(defaultDateFormat);
		Column<Corso, Date> endColumn = new Column<Corso, Date>(endDateCell) {

			@Override
			public Date getValue(Corso corso) {
				return corso.getEndDate();
			}
		};

		ClickableTextCell clickableTextCell = new ClickableTextCell();

		Column<Corso, String> coprofessorColumn = new Column<Corso, String>(clickableTextCell) {

			@Override
			public String getValue(Corso corso) {
				return corso.getEmailCodocente();
			}
		};

		//Bottone per modificare corso
		Column<Corso, Corso> editCorsoColumn = new Column<Corso, Corso>(
				new ActionCell<Corso>("Modifica", new Delegate<Corso>() {

					@Override
					public void execute(Corso corso) {
						showEditingCorsoPanel(corso);
					}
				})
				){
			@Override
			public Corso getValue(Corso corso) {
				return corso;
			}
		};
		Column<Corso, Corso> deleteCorsoColumn = new Column<Corso, Corso>(
				new ActionCell<Corso>("Elimina", new Delegate<Corso>() {

					@Override
					public void execute(Corso corso) {
						gs.deleteCorso(corso, new AsyncCallback<Void>() {

							@Override
							public void onFailure(Throwable caught) {
								Window.alert("Errore connessione server");
							}

							@Override
							public void onSuccess(Void result) {
								showFindCorsiPanel();
							}
						});
					}
				})
				){
			@Override
			public Corso getValue(Corso corso) {
				return corso;
			}
		};
		// Bottone per visualizzare e modificare esame
		Column<Corso, Corso> editExamColumn = new Column<Corso, Corso>(
				new ActionCell<Corso>("Visualizza esame", new Delegate<Corso>() {

					@Override
					public void execute(Corso corso) {
						showEsamePanel(corso);
					}
				})
				){
			@Override
			public Corso getValue(Corso corso) {
				return corso;
			}
		};

		//Aggiungo le colonne alle tabelle
		ctCorsi.addColumn(nameColumn, "Nome");
		ctCorsi.addColumn(descColumn, "Descrizione");
		ctCorsi.addColumn(startColumn, "Data di inizio");
		ctCorsi.addColumn(endColumn, "Data di fine");
		ctCorsi.addColumn(coprofessorColumn, "Codocente");
		ctCorsi.addColumn(editCorsoColumn);
		ctCorsi.addColumn(deleteCorsoColumn);
		ctCorsi.addColumn(editExamColumn);

		nameColumn.setSortable(true);

		gs.getAllCorsi(new AsyncCallback<ArrayList<Corso>>() {

			@Override
			public void onFailure(Throwable caught) {
				Window.alert("Errore connessione server");
			}

			@Override
			public void onSuccess(ArrayList<Corso> allCorsi) {

				gs.getMyCorsi(emailAccount, new AsyncCallback<ArrayList<String>>() {

					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Errore connessione server");
					}

					@Override
					public void onSuccess(ArrayList<String> listaNomiCorsiDocente) {
						// aggiungo solo i corsi che appartengono al docente
						ArrayList<Corso> listaCorsiDocente = new ArrayList<>();

						for(Corso corso : allCorsi) {
							// se non ha corsi esce subito
							if(listaNomiCorsiDocente == null) {
								break;
							}
							if(listaNomiCorsiDocente.contains(corso.getNome())) {
								listaCorsiDocente.add(corso);
							}
						}
						dataProvider.getList().clear();
						dataProvider.getList().addAll(listaCorsiDocente);

						Comparator<Corso> comparator = new Comparator<Corso>() {

							@Override
							public int compare(Corso o1, Corso o2) {
								if(o1 == o2) {
									return 0;
								}

								if(o1 != null) {
									if(o2 != null) {
										return o1.getNome().compareTo(o2.getNome());
									} else {
										return 1;
									}
								}
								return -1;
							}
						};
						columnSortHandler.setComparator(nameColumn, comparator);
						ctCorsi.addColumnSortHandler(columnSortHandler);
						ctCorsi.getColumnSortList().push(nameColumn);
						ctCorsi.redraw();
					}
				});
			}
		});

		//bottone per aggiornare
		Button btnLista = new Button("Aggiorna");
		btnLista.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				showFindCorsiPanel();
			}
		});

		// bottone per la ricerca manuale
		Button btnSingleSearch = new Button("Cerca");
		btnSingleSearch.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				centerPanel.clear();
				showSingleSearchCorsoPanel();
			}
		});

		tablePanel.add(ctCorsi, DockPanel.CENTER);
		tablePanel.add(btnLista, DockPanel.EAST);
		tablePanel.add(btnSingleSearch, DockPanel.WEST);
		tablePanel.add(pager, DockPanel.SOUTH);
		centerPanel.add(tablePanel);
	}

	private void showEsamePanel(Corso corso) {

		centerPanel.clear();

		gs.getEsame(corso, new AsyncCallback<Esame>() {

			@Override
			public void onFailure(Throwable caught) {
				Window.alert("Errore connessione server");
			}

			@Override
			public void onSuccess(Esame esame) {
				// se esame non esiste
				if(esame == null) {
					showNewExamPanel(corso);
				}
				// se esame esiste
				else {
					showExistingExamPanel(esame);
				}
			}
		});
	}

	private void showExistingExamPanel(Esame esame) {

		centerPanel.clear();

		FlexTable examInfoTable = new FlexTable();
		// bottone per eliminare esame
		Button btnDeleteExam = new Button("Elimina");
		btnDeleteExam.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				gs.deleteEsame(esame, new AsyncCallback<Void>() {

					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Errore connessione server");
					}

					@Override
					public void onSuccess(Void result) {
						showFindCorsiPanel();
					}
				});
			}
		});
		// bottone per modificare esame
		Button btnEditExam   = new Button("Modifica");
		btnEditExam.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				showEditingExamPanel(esame);
			}
		});

		// bottone indietro che torna a tabella corsi
		Button btnBackToCorsi = new Button("Indietro");
		Button btnInserisciVoti = new Button("Inserisci voti");
		btnBackToCorsi.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				showFindCorsiPanel();
			}
		});

		btnInserisciVoti.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				showVoteTable(esame);
			}
		});

		examInfoTable.setText(0, 0, "Corso: ");
		examInfoTable.setText(0, 1, esame.getNomeCorsoRif());
		examInfoTable.setText(1, 0, "Data: ");
		examInfoTable.setText(1, 1, defaultDateFormat.format(esame.getData()));
		examInfoTable.setText(2, 0, "Ora inizio: ");
		examInfoTable.setText(2, 1, esame.getOraInizio()[0] + ":" + esame.getOraInizio()[1]);
		examInfoTable.setText(3, 0, "Durata: ");
		String durataString = esame.getDurata()[0] + ":" + esame.getDurata()[1];
		examInfoTable.setText(3, 1, durataString);
		examInfoTable.setText(4, 0, "Aula: ");
		examInfoTable.setText(4, 1, esame.getAula().toString());
		examInfoTable.setWidget(5, 0, btnDeleteExam);
		examInfoTable.setWidget(5, 1, btnEditExam);
		examInfoTable.setWidget(6, 0, btnBackToCorsi);
		examInfoTable.setWidget(6, 1, btnInserisciVoti);

		centerPanel.add(examInfoTable);
	}

	private void showNewExamPanel(Corso corso) {

		centerPanel.clear();

		FlexTable examInfoTable = new FlexTable();

		Button btnCreateExam = new Button("Crea");
		examInfoTable.setText(0, 0, "Non ci sono esami per questo corso, crearne uno?");
		examInfoTable.setWidget(1, 0, btnCreateExam);
		btnCreateExam.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				showCreateExamPanel(corso);
			}
		});

		// bottone indietro che torna a tabella corsi
		Button btnBackToCorsi = new Button("Indietro");
		btnBackToCorsi.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				showFindCorsiPanel();
			}
		});

		examInfoTable.setWidget(2, 0, btnBackToCorsi);

		centerPanel.add(examInfoTable);
	}

	private void showCreateExamPanel(Corso corso) {

		centerPanel.clear();
		
		//factory per creare l'esame
		CreaEsame factoryCreaEsame =new CreaEsame();
		
		FlexTable createExamPanel = new FlexTable();
		DateBox dbDate = new DateBox();
		dbDate.setFormat(new DateBox.DefaultFormat(defaultDateFormat));
		
		//Inserimento di listBox per selezionare l'orario e la durata dell'esame
		ListBox lbHoursDurata = new ListBox();
		ListBox lbMinutesDurata = new ListBox();
		ListBox lbHoursStart = new ListBox();
		ListBox lbMinutesStart = new ListBox();
		fillListBox(lbHoursDurata, 23);
		fillListBox(lbMinutesDurata, 59);
		fillListBox(lbHoursStart, 23);
		fillListBox(lbMinutesStart, 59);

		ListBox lbAula = new ListBox();
		ClassRoomType[] listaAule = ClassRoomType.values();
		for(ClassRoomType aula : listaAule) {
			lbAula.addItem(aula.toString());
		}
		Button btnCreate = new Button("Crea");
		btnCreate.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				Esame esame =factoryCreaEsame.creaEsame(emailAccount,
						dbDate.getValue(),
						lbHoursDurata.getSelectedValue(),
						lbMinutesDurata.getSelectedValue(),
						lbHoursStart.getSelectedValue(),
						lbMinutesStart.getSelectedValue(),
						listaAule[lbAula.getSelectedIndex()],
						corso);

				gs.createEsame(esame, new AsyncCallback<Void>() {

					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Errore connessione server");
					}

					@Override
					public void onSuccess(Void result) {
						showEsamePanel(corso);
					}
				});
			}
		});

		createExamPanel.setText(  0, 0, "Corso: ");
		createExamPanel.setText(  0, 1, corso.getNome());
		createExamPanel.setText(  1, 0, "Data: ");
		createExamPanel.setWidget(1, 1, dbDate);
		createExamPanel.setText(2, 0, "Ora inizio: ");
		HorizontalPanel startPanel = new HorizontalPanel();
		startPanel.add(lbHoursStart);
		startPanel.add(lbMinutesStart);
		createExamPanel.setWidget(2, 1, startPanel);
		createExamPanel.setText(  3, 0, "Durata: ");
		HorizontalPanel durataPanel = new HorizontalPanel();
		durataPanel.add(lbHoursDurata);
		durataPanel.add(lbMinutesDurata);
		createExamPanel.setWidget(3, 1, durataPanel);
		createExamPanel.setText(  4, 0, "Aula: ");
		createExamPanel.setWidget(4, 1, lbAula);
		createExamPanel.setWidget(5, 0, btnCreate);

		centerPanel.add(createExamPanel);
	}

	private void showSingleSearchCorsoPanel() {
		centerPanel.clear();

		FlexTable findCorsoPanel = new FlexTable();
		TextBox tbFind = new TextBox();
		Button btnFind = new Button("Cerca");
		findCorsoPanel.setText(0, 0, "Nome: ");
		findCorsoPanel.setWidget(0, 1, tbFind);
		findCorsoPanel.setWidget(1, 0, btnFind);

		btnFind.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				showSingleCorsoPanel(tbFind.getText());
			}
		});
		centerPanel.add(findCorsoPanel);
	}

	private void showSingleCorsoPanel(String nomeCorso) {
		centerPanel.clear();

		FlexTable corsoPanel = new FlexTable();
		Button 		 btnFind = new Button("Cerca");

		gs.getCorso(nomeCorso, new AsyncCallback<Corso>() {

			@Override
			public void onFailure(Throwable caught) {
				Window.alert("Errore connessione server");
			}

			@Override
			public void onSuccess(Corso corso) {

				if(corso == null) {
					// ripulisco findPanel
					corsoPanel.remove(btnFind);

					Button btnBack = new Button("Indietro");
					corsoPanel.setText(0, 0, "Corso non esistente");
					corsoPanel.setWidget(1, 0, btnBack);
					btnBack.addClickHandler(new ClickHandler() {

						@Override
						public void onClick(ClickEvent event) {
							showFindCorsiPanel();
						}
					});
					return;
				}
				Button btnEdit = new Button("Modifica");
				btnEdit.addClickHandler(new ClickHandler() {

					@Override
					public void onClick(ClickEvent event) {
						centerPanel.clear();
						showEditingCorsoPanel(corso);
					}
				});
				corsoPanel.setText(2, 0, "Nome: ");
				corsoPanel.setText(2, 1, corso.getNome());
				corsoPanel.setText(3, 0, "Descrizione: ");
				corsoPanel.setText(3, 1, corso.getDescrizione());
				corsoPanel.setText(4, 0, "Data inizio: ");
				corsoPanel.setText(4, 1, defaultDateFormat.format(corso.getInitDate()).toString());
				corsoPanel.setText(5, 0, "Data fine: ");
				corsoPanel.setText(5, 1, defaultDateFormat.format(corso.getEndDate()).toString());
				corsoPanel.setText(6, 0, "Codocente: ");
				corsoPanel.setText(6, 1, corso.getEmailCodocente());
				corsoPanel.setWidget(7, 0, btnEdit);
			}
		});
		centerPanel.add(corsoPanel);
	}



	private void showEditingCorsoPanel(Corso corso) {
		FlexTable editCorsoPanel = new FlexTable();
		
		centerPanel.remove(editCorsoPanel);

		TextArea tbDescription = new TextArea();
		DateBox		   dbStart = new DateBox();
		DateBox			 dbEnd = new DateBox();
		Button		btnConfirm = new Button("Salva modifiche");
		Button		  btnClose = new Button("Chiudi");

		btnConfirm.setEnabled(false);

		MultiWordSuggestOracle oracle = new MultiWordSuggestOracle();

		gs.getProfessors(new AsyncCallback<ArrayList<Account>>() {

			@Override
			public void onFailure(Throwable caught) {
				Window.alert("Errore connessione server");
			}

			@Override
			public void onSuccess(ArrayList<Account> listaDocenti) {

				for(Account docente : listaDocenti) {
					oracle.add(docente.getNome() + " " + docente.getCognome()
					+ " (" + docente.getEmail() + ")");
				}
			}
		});

		SuggestBox sbProfessor = new SuggestBox(oracle);

		//Setto il formato delle date giorno/mese/anno
		dbStart.setFormat(new DateBox.DefaultFormat(defaultDateFormat));
		dbEnd.setFormat(new DateBox.DefaultFormat(defaultDateFormat));

		editCorsoPanel.setText(  0,0, "Nome:");
		editCorsoPanel.setText(0,1, corso.getNome());
		editCorsoPanel.setText(  1,0, "Descrizione:");
		editCorsoPanel.setWidget(1,1, tbDescription);
		editCorsoPanel.setText(  2,0, "Data Inizio:");
		editCorsoPanel.setWidget(2,1, dbStart);
		editCorsoPanel.setText(  3,0, "Data Fine:");
		editCorsoPanel.setWidget(3,1, dbEnd);
		editCorsoPanel.setText(  4, 0, "Codocente: ");
		editCorsoPanel.setWidget(4, 1, sbProfessor);
		editCorsoPanel.setWidget(5,0, btnConfirm);
		editCorsoPanel.setWidget(5,1, btnClose);



		// salvo valori originali
		String oldDesc = corso.getDescrizione();
		Date  oldStart = corso.getInitDate();
		Date    oldEnd = corso.getEndDate();
		String oldCodocente = corso.getEmailCodocente();

		tbDescription.setText(oldDesc);
		dbStart.setValue(oldStart);
		dbEnd.setValue(oldEnd);
		sbProfessor.setValue(oldCodocente);

		KeyUpHandler keyUpHandler = new KeyUpHandler() {

			@Override
			public void onKeyUp(KeyUpEvent event) {

				if(tbDescription.getText() != oldDesc || sbProfessor.getValue() != oldCodocente){
					btnConfirm.setEnabled(true);

				} else {
					btnConfirm.setEnabled(false);
				}
			}
		};

		tbDescription.addKeyUpHandler(keyUpHandler);
		sbProfessor.addKeyUpHandler(keyUpHandler);

		ValueChangeHandler<Date> valueChangeHandler = new ValueChangeHandler<Date>() {

			@Override
			public void onValueChange(ValueChangeEvent<Date> event) {

				if(dbStart.getValue() != oldStart) {
					btnConfirm.setEnabled(true);

				} else if(dbEnd.getValue() != oldEnd) {
					btnConfirm.setEnabled(true);

				} else btnConfirm.setEnabled(false);
			}
		};

		dbStart.addValueChangeHandler(valueChangeHandler);
		dbEnd.addValueChangeHandler(valueChangeHandler);

		btnConfirm.addClickHandler(new ClickHandler() {

			String emailCodocente = null;

			@Override
			public void onClick(ClickEvent event) {

				// data fine deve essere dopo data inizio
				if(!dbEnd.getValue().after(dbStart.getValue())) {
					editCorsoPanel.setText(6, 1, "La data di fine corso deve essere successiva alla data di inizio");
					return;
				}

				if(sbProfessor.getText().contains("(")){
					emailCodocente = sbProfessor.getText().substring(sbProfessor.getText().indexOf("(")+1, sbProfessor.getText().length()-1);

				} else {
					emailCodocente = sbProfessor.getText();
				}
				

				corso.setDescrizione(tbDescription.getText());
				corso.setInitDate(dbStart.getValue());
				corso.setEndDate(dbEnd.getValue());
				corso.setEmailCodocente(emailCodocente);

				gs.editCorso(corso, new AsyncCallback<Void>() {

					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Errore connessione server");
					}

					@Override
					public void onSuccess(Void result) {
						Window.alert("Corso modificato con successo");
						
						// nascondo editCorsoTable e aggiorno la tabella
						centerPanel.clear();
						showFindCorsiPanel();
					}
				});
			}
		});

		btnClose.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				showFindCorsiPanel();
			}
		});
		centerPanel.add(editCorsoPanel);
	}

	private void showEditingExamPanel(Esame esame) {

		centerPanel.clear();

		FlexTable editingExamTable = new FlexTable();
		DateBox dbDate = new DateBox();
		dbDate.setFormat(new DateBox.DefaultFormat(defaultDateFormat));

		ListBox lbHoursDurata = new ListBox();
		ListBox lbMinutesDurata = new ListBox();
		ListBox lbHoursStart = new ListBox();
		ListBox lbMinutesStart = new ListBox();
		ListBox lbAula = new ListBox();
		ClassRoomType[] listaAule = ClassRoomType.values();

		for(ClassRoomType aula : listaAule) {
			lbAula.addItem(aula.toString());
		}

		fillListBox(lbHoursStart, 23);
		fillListBox(lbMinutesStart, 59);
		fillListBox(lbHoursDurata, 23);
		fillListBox(lbMinutesDurata, 59);

		Button btnBack = new Button("Indietro");
		btnBack.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				showFindCorsiPanel();
			}
		});

		Button btnConfirm = new Button("Salva modifiche");

		btnConfirm.setEnabled(false);
		btnConfirm.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {

				esame.setData(dbDate.getValue());
				String[] durata = {lbHoursDurata.getSelectedValue(), lbMinutesDurata.getSelectedValue()}; 
				String[] oraInizio = {lbHoursStart.getSelectedValue(), lbMinutesStart.getSelectedValue()};
				esame.setDurata(durata);
				esame.setOraInizio(oraInizio);

				for(int i = 0; i < listaAule.length; i++) {
					if(lbAula.getSelectedValue().equals(listaAule[i].toString())){
						esame.setAula(listaAule[i]);
					}
				}

				// salva modifiche
				gs.editEsame(esame, new AsyncCallback<Void>() {

					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Errore connessione server");
					}

					@Override
					public void onSuccess(Void result) {
						// torna a schermata esame
						gs.getCorso(esame.getNomeCorsoRif(), new AsyncCallback<Corso>() {

							@Override
							public void onFailure(Throwable caught) {
								Window.alert("Errore connessione server");
							}

							@Override
							public void onSuccess(Corso corso) {
								showEsamePanel(corso);
							}
						});
					}
				});
			}
		});

		// salvo valori originali
		Date oldDate = esame.getData();
		String[] oldDurata = esame.getDurata();
		String[] oldStart = esame.getOraInizio();

		ClassRoomType oldAula = esame.getAula();
		
		//Quando i valori vengono modificati il bottone diventa abilitato
		ChangeHandler changeHandler = new ChangeHandler() {

			@Override
			public void onChange(ChangeEvent event) {
				if(lbAula.getSelectedValue().toString() != (oldAula.toString())) {
					btnConfirm.setEnabled(true);
				} else {
					String[] newDurata = {lbHoursDurata.getSelectedValue(), lbMinutesDurata.getSelectedValue()};
					String[] newStart = {lbHoursStart.getSelectedValue(), lbMinutesStart.getSelectedValue()};
					if(!Arrays.equals(newDurata, oldDurata) || !Arrays.equals(newStart, oldStart)) {
						btnConfirm.setEnabled(true);

					} else {
						btnConfirm.setEnabled(false);
					}
				}
			}
		};
		lbAula.addChangeHandler(changeHandler);
		lbHoursDurata.addChangeHandler(changeHandler);
		lbMinutesDurata.addChangeHandler(changeHandler);
		lbHoursStart.addChangeHandler(changeHandler);
		lbMinutesStart.addChangeHandler(changeHandler);

		ValueChangeHandler<Date> valueChangeHandler = new ValueChangeHandler<Date>() {

			@Override
			public void onValueChange(ValueChangeEvent<Date> event) {

				if(dbDate.getValue() != oldDate) {
					btnConfirm.setEnabled(true);

				} else btnConfirm.setEnabled(false);
			}
		};
		dbDate.addValueChangeHandler(valueChangeHandler);

		// mostro i valori originali
		dbDate.setValue(oldDate);
		// lbHoursDurata
		for(int i = 0; i < lbHoursDurata.getItemCount(); i++) {
			if(lbHoursDurata.getValue(i).equals(oldDurata[0])) {
				lbHoursDurata.setSelectedIndex(i);
			}
		}
		// lbMinutesDurata
		for(int i = 0; i < lbMinutesDurata.getItemCount(); i++) {
			if(lbMinutesDurata.getValue(i).equals(oldDurata[1])) {
				lbMinutesDurata.setSelectedIndex(i);
			}
		}
		// lbHoursStart
		for(int i = 0; i < lbHoursStart.getItemCount(); i++) {
			if(lbHoursStart.getValue(i).equals(oldStart[0])) {
				lbHoursStart.setSelectedIndex(i);
			}
		}
		// lbMinutesStart
		for(int i = 0; i < lbMinutesStart.getItemCount(); i++) {
			if(lbMinutesStart.getValue(i).equals(oldStart[1])) {
				lbMinutesStart.setSelectedIndex(i);
			}
		}
		// aula
		for(int i = 0; i < listaAule.length; i++) {
			if(listaAule[i].equals(oldAula)) {
				lbAula.setSelectedIndex(i);
			}
		}

		editingExamTable.setText(  0, 0, "Modifica esame");
		editingExamTable.setText(  1, 0, "Corso: ");
		editingExamTable.setText(  1, 1, esame.getNomeCorsoRif());
		editingExamTable.setText(  2, 0, "Data: ");
		editingExamTable.setWidget(2, 1, dbDate);
		editingExamTable.setText(  3, 0, "Ora inizio: ");
		HorizontalPanel startPanel = new HorizontalPanel();
		startPanel.add(lbHoursStart);
		startPanel.add(lbMinutesStart);
		editingExamTable.setWidget(3, 1, startPanel);
		editingExamTable.setText(  4, 0, "Durata: ");
		HorizontalPanel durataPanel = new HorizontalPanel();
		durataPanel.add(lbHoursDurata);
		durataPanel.add(lbMinutesDurata);
		editingExamTable.setWidget(4, 1, durataPanel);
		editingExamTable.setText(  5, 0, "Aula: ");
		editingExamTable.setWidget(5, 1, lbAula);
		editingExamTable.setWidget(6, 0, btnBack);
		editingExamTable.setWidget(6, 1, btnConfirm);

		centerPanel.add(editingExamTable);
	}

	//Metodo per disegnare la tabella per inserire i voti
	private void showVoteTable(Esame esame) {
		centerPanel.clear();
		
		ArrayList<String> selezioneVoti = new ArrayList<>(Arrays.asList("insufficente","18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "30elode")); 

		HorizontalPanel editingExamTable = new HorizontalPanel();

		ListDataProvider<String> dataProvider = new ListDataProvider<>();
		CellTable<String> ctVoti= new CellTable<String>();

		dataProvider.addDataDisplay(ctVoti);
		TextColumn<String> studentColumn= new TextColumn<String>() {

			@Override
			public String getValue(String emailStud) {
				return emailStud;
			}
		}; 
		SelectionCell selectGrades =new  SelectionCell(selezioneVoti);
		Column<String,String> gradesColumn = new Column<String, String>(selectGrades) {

			@Override
			public String getValue(String value) {
				return selectGrades.getViewData(value);
			}
		};

		ctVoti.addColumn(studentColumn, "E-mail");
		ctVoti.addColumn(gradesColumn,"Voto");


		Button btnInviaVoti = new Button("Invia alla segreteria");
		Button btnBack = new Button("Indietro");
		btnBack.addClickHandler(new ClickHandler() {

			@Override
			public void onClick(ClickEvent event) {
				showFindCorsiPanel();
			}
		});


		gs.getIscrittiEsame(esame.getNomeCorsoRif(), new AsyncCallback<ArrayList<String>>() {
			
			@Override
			public void onSuccess(ArrayList<String> listaIscritti) {
				/*
				 * grades � una matrice che contiene un array di iscritti: [0] email studente
				 * 														   [1] voto
				 */
				String [][] grades = new String [listaIscritti.size()][2];
				
				/*Setto tutti i voti di default ad insufficiente*/
				
				for(int i=0; i < listaIscritti.size();i ++) {
					grades[i][0] = listaIscritti.get(i);
					grades[i][1] = selezioneVoti.get(0); 
				}
				
				btnInviaVoti.addClickHandler(new ClickHandler() {

					@Override
					public void onClick(ClickEvent event) {
						
						ArrayList<Voto> listaVoti= new ArrayList<Voto>();
						
						for(int i=0; i < grades.length;i ++) {
							Voto voto= new Voto(grades[i][0],esame.getNomeCorsoRif());
							voto.setVoto(grades[i][1]);
							listaVoti.add(voto);
							
						}	
						
						gs.saveGradesProfessorSide(esame.getNomeCorsoRif(), listaVoti, new AsyncCallback<Void>() {
							
							@Override
							public void onSuccess(Void result) {
								showExistingExamPanel(esame);
							}
							
							@Override
							public void onFailure(Throwable caught) {
								Window.alert("Errore connessione server");
							}
						});
					}
				});



				gradesColumn.setFieldUpdater(new FieldUpdater<String, String>() {

					@Override
					public void update(int index, String email, String voto) {
						grades[index][0] = email;
						grades[index][1]= voto;
					}
				});

				dataProvider.getList().clear();
				dataProvider.getList().addAll(listaIscritti);
			}

			@Override
			public void onFailure(Throwable caught) {
				Window.alert("Errore connessione server");
			}
		});
		editingExamTable.setSpacing(10);
		editingExamTable.add(ctVoti);
		editingExamTable.add(btnInviaVoti);
		editingExamTable.add(btnBack);

		centerPanel.add(editingExamTable);

	}
	//Metodo per riempire le list box per ore o minuti
	private void fillListBox(ListBox lb , int range) {

		for(int i=0; i <= range; i++ ) {
			if(i <= 9) {
				lb.addItem("0"+i);
			} else {
				lb.addItem(String.valueOf(i));
			}
		}
	}
	
}