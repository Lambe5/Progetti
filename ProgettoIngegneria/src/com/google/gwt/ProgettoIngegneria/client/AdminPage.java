package com.google.gwt.ProgettoIngegneria.client;

import java.util.ArrayList;

import com.google.gwt.cell.client.ActionCell;
import com.google.gwt.cell.client.ActionCell.Delegate;
import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.event.dom.client.KeyUpEvent;
import com.google.gwt.event.dom.client.KeyUpHandler;
import com.google.gwt.user.cellview.client.CellTable;
import com.google.gwt.user.cellview.client.Column;
import com.google.gwt.user.cellview.client.TextColumn;
import com.google.gwt.user.cellview.client.ColumnSortEvent.ListHandler;
import com.google.gwt.user.cellview.client.SimplePager;
import com.google.gwt.user.cellview.client.SimplePager.TextLocation;
import com.google.gwt.user.client.Command;
import com.google.gwt.user.client.Window;
import com.google.gwt.user.client.rpc.AsyncCallback;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.DockPanel;
import com.google.gwt.user.client.ui.FlexTable;
import com.google.gwt.user.client.ui.ListBox;
import com.google.gwt.user.client.ui.MenuBar;
import com.google.gwt.user.client.ui.TextBox;
import com.google.gwt.user.client.ui.VerticalPanel;
import com.google.gwt.view.client.ListDataProvider;
import com.google.gwt.view.client.ProvidesKey;
import java.util.Comparator;

public class AdminPage extends Composite {

	private final static GreetingServiceAsync gs = GWT.create(GreetingService.class);
	
	Amministratore amm = new Amministratore();
	
	DockPanel mainPanel;
	VerticalPanel centerPanel;
	DockPanel tablePanel;
	
	final String homePageString = "HomePage";

	public AdminPage() {
			
		  mainPanel = new DockPanel();
		centerPanel = new VerticalPanel();
		 tablePanel = new DockPanel();

		mainPanel.setSpacing(10);
		 
		Button btnLogOut = new Button("Log out");
		btnLogOut.addClickHandler(new ClickHandler() {
			
			@Override
			public void onClick(ClickEvent event) {
				ControllerPages.showThisPage(homePageString, "");
			}
		});
		mainPanel.add(btnLogOut, DockPanel.NORTH);
		mainPanel.add(centerPanel, DockPanel.CENTER);
		mainPanel.add(tablePanel, DockPanel.EAST);
		
		showMenuPanel();
		
		//stampa account nella celltable
		showAccountsTable();
		
		initWidget(mainPanel);
	}
	
	public String createEmail(String nome, String cognome, String type) {
		if(nome == null || cognome == null || type == null || nome == "" || cognome == "" || type == "")
			return "Input non corretto";
		else {
			int n = 0;
			boolean correctName = false;
			boolean correctSurname = false;
			
			for(int i = 0; i < nome.length(); i++) {
				n = (int)nome.charAt(i);
				if((n >= 97 && n <= 122) || (n >= 65 && n <= 90))
					correctName = true;
				else {
					correctName = false;
					break;
				}
			}
			
			for(int i = 0; i < cognome.length(); i++) {
				n = (int)nome.charAt(i);
				if((n >= 97 && n <= 122) || (n >= 65 && n <= 90))
					correctSurname = true;
				else {
					correctSurname = false;
					break;
				}
			}
			
			if(!correctName || !correctSurname)
				return "Input non corretto";
			else return  nome.toLowerCase() + "." + cognome.toLowerCase() + "@" + type + ".uni.it";
		}		
	}

	
	private void showMenuPanel() {
		// creazione menuPanel
		MenuBar menuPanel = new MenuBar(true);
		
		menuPanel.addItem("Crea nuovo account", new Command() {

			@Override
			public void execute() {
				showCreatePanel();
			}
		});
		menuPanel.addSeparator();
		menuPanel.addItem("Cerca account esistente", new Command() {

			@Override
			public void execute() {
				showFindPanel();
			}
		});

		mainPanel.add(menuPanel, DockPanel.WEST);
	}
	
	private void showCreatePanel() {
		
		centerPanel.clear();
		
		FlexTable createPanel = new FlexTable();
		TextBox 	   tbNome = new TextBox();
		TextBox 	tbCognome = new TextBox();
		ListBox 	lbAccount = new ListBox();
		String[] listaAccount = {"Studente", "Docente", "Segreteria"};
		Button btnCreate = new Button("Crea Account");
		
		for (int i = 0; i < listaAccount.length; i++) {
			lbAccount.addItem(listaAccount[i]);
		}

		createPanel.setText(0, 0, "Nome");
		createPanel.setWidget(0, 1, tbNome);
		createPanel.setText(1, 0, "Cognome");
		createPanel.setWidget(1, 1, tbCognome);
		createPanel.setText(2, 0, "Tipo di utente");
		createPanel.setWidget(2, 1, lbAccount);
		createPanel.setText(3, 0, "Email");

		btnCreate.addClickHandler(new ClickHandler() {

			public void onClick(ClickEvent event) {
				int categoryIndex = lbAccount.getSelectedIndex();
				String tipoString = "";
				Account account = null;
				String emailCreata = "";

				TipoUtente tipoUtente = null;
				switch (listaAccount[categoryIndex]) {
				case "Studente":
					tipoString = "stud";
					tipoUtente = TipoUtente.STUDENTE;
					break;
				case "Docente":
					tipoString = "doc";
					tipoUtente = TipoUtente.DOCENTE;
					break;
				case "Segreteria":
					tipoString = "seg";
					tipoUtente = TipoUtente.SEGRETERIA;
					break;
				}
				emailCreata = createEmail(tbNome.getText(), tbCognome.getText(), tipoString);
				account = amm.creaUtente(tipoUtente, emailCreata, tbNome.getText(), tbCognome.getText());

				gs.createAccount(account, new AsyncCallback<String>() {

					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Account non registrato: failure: " + caught.getMessage());
					}

					@Override
					public void onSuccess(String email) {

						gs.getInfo(email, new AsyncCallback<Account>() {

							@Override
							public void onFailure(Throwable caught) {
								Window.alert("Errore connessione server");
							}

							@Override
							public void onSuccess(Account account) {
								createPanel.setText(3, 1, email);
								createPanel.setText(5,0,"Riepilogo informazioni:");
								createPanel.setText(5,1,"Nome: " + account.getNome());
								createPanel.setText(6,1,"Cognome: " + account.getCognome());
								createPanel.setText(7,1,"Password: " + account.getPassword());
							}
						});
						showAccountsTable();
					}
				});
			}
		});
		createPanel.setWidget(4, 0, btnCreate);
		centerPanel.add(createPanel);
	}
	
	private void showFindPanel() {
		
		centerPanel.clear();
		
		//Pannello per cercare Account
		FlexTable findPanel = new FlexTable();
		TextBox tbFindEmail = new TextBox();
		Button 		btnFind = new Button("Cerca");
		Button btnEdit = new Button("Modifica");
		btnFind.addClickHandler(new ClickHandler() {
			
			@Override
			public void onClick(ClickEvent event) {
				gs.getInfo(tbFindEmail.getText(), new AsyncCallback<Account>() {

					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Errore connessione server");
					}

					@Override
					public void onSuccess(Account account) {
						
						if(account == null) {
							
							// ripulisco findPanel
							findPanel.remove(tbFindEmail);
							findPanel.remove(btnFind);
							findPanel.remove(btnEdit);
							findPanel.setText(5, 0, "");
							findPanel.setText(6, 0, "");
							findPanel.setText(7, 0, "");
							findPanel.setText(5, 1, "");
							findPanel.setText(6, 1, "");
							findPanel.setText(7, 1, "");
							
							Button btnBack = new Button("Indietro");
							findPanel.setText(0, 0, "Account non esistente");
							findPanel.setWidget(1, 0, btnBack);
							btnBack.addClickHandler(new ClickHandler() {
								
								@Override
								public void onClick(ClickEvent event) {
									showFindPanel();
								}
							});
							return;
						}
						findPanel.setText(5, 0, "Nome");
						findPanel.setText(5, 1, account.getNome());
						findPanel.setText(6, 0, "Cognome");
						findPanel.setText(6, 1, account.getCognome());
						findPanel.setText(7, 0, "Email");
						findPanel.setText(7, 1, account.getEmail());
						findPanel.setWidget(8, 0, btnEdit);
					}
				});
			}
		});
		btnEdit.addClickHandler(new ClickHandler() {
			
			@Override
			public void onClick(ClickEvent event) {
				gs.getInfo(findPanel.getText(7, 1), new AsyncCallback<Account>() {

					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Errore connessione server");
					}

					@Override
					public void onSuccess(Account account) {
						showEditingPanel(account);
					}
				});
			}
		});
		
		findPanel.setText(0, 0, "Email");
		findPanel.setWidget(0, 1, tbFindEmail);
		findPanel.setWidget(1, 0, btnFind);
		
		centerPanel.add(findPanel);
		
	}
	
	private void showEditingPanel(Account account) {

		FlexTable editTable = new FlexTable();
		TextBox tbName = new TextBox();
		TextBox tbSurname = new TextBox();	
		Button btnConfirm = new Button("Salva modifiche");
		btnConfirm.setEnabled(false);
		
		editTable.setText(0, 0, "Nome:");
		editTable.setWidget(0, 1, tbName);
		editTable.setText(1, 0, "Cognome:");
		editTable.setWidget(1, 1, tbSurname);
		editTable.setText(2, 0, "Email:");
		editTable.setText(2, 1, account.getEmail());
		editTable.setText(3, 0, "password:");
		editTable.setText(3, 1, account.getPassword());
		editTable.setWidget(4, 0, btnConfirm);
		
		tbName.setText(account.getNome());
		tbSurname.setText(account.getCognome());
		
		String oldName = account.getNome();
		String oldSurname = account.getCognome();
		
		// se non ci sono modifiche il bottone e' disabilitato
		KeyUpHandler keyUpHandler = new KeyUpHandler() {
			
			@Override
			public void onKeyUp(KeyUpEvent event) {
				if(tbName.getText() != oldName || tbSurname.getText() != oldSurname) {
					btnConfirm.setEnabled(true);
				} else {
					btnConfirm.setEnabled(false);
				}
			}
		};
		
		tbName.addKeyUpHandler(keyUpHandler);
		tbSurname.addKeyUpHandler(keyUpHandler);
		
		btnConfirm.addClickHandler(new ClickHandler() {
			
			@Override
			public void onClick(ClickEvent event) {
				account.setNome(tbName.getText());
				account.setCognome(tbSurname.getText());
				
				gs.editAccount(account, new AsyncCallback<Void>() {

					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Errore connessione server");
					}

					@Override
					public void onSuccess(Void result) {

						Window.alert("Account modificato con successo");
						// nascondo ediTable
						centerPanel.clear();
						showAccountsTable();
					}
				});
			}
		});
		
		centerPanel.clear();
		centerPanel.add(editTable);
	}
	
	// crea tabella con tutti gli account
	private void showAccountsTable() {
		
		tablePanel.clear();
		
		ListDataProvider<Account> dataProvider = new ListDataProvider<>();

		ListHandler<Account> columnSortHandler = new ListHandler<>(dataProvider.getList());
		
		ProvidesKey<Account> keyProvider = new ProvidesKey<Account>() {

			@Override
			public Object getKey(Account item) {
				return (item == null) ? null : item.id;
			}
		};
		
		CellTable<Account> ctFind = new CellTable<Account>(keyProvider);
		ctFind.setWidth("100%");
		ctFind.setAutoHeaderRefreshDisabled(true);
		
		// pager per scorrere pagine tabella
		SimplePager.Resources pagerResources = GWT.create(SimplePager.Resources.class);
	    SimplePager pager = new SimplePager(TextLocation.CENTER, pagerResources, false, 0, true);
	    pager.setDisplay(ctFind);

		dataProvider.addDataDisplay(ctFind);
		
		Button btnLista = new Button("Aggiorna");
		btnLista.addClickHandler(new ClickHandler() {
			
			@Override
			public void onClick(ClickEvent event) {
				showAccountsTable();
			}
		});

		TextColumn<Account> nameColumn = new TextColumn<Account>() {
			@Override
			public String getValue(Account account) {
				return account.getNome();
			}
		};
		
		TextColumn<Account> surnameColumn = new TextColumn<Account>() {

			@Override
			public String getValue(Account account) {
				return account.getCognome();
			}
		};

		TextColumn<Account> emailColumn = new TextColumn<Account>() {

			@Override
			public String getValue(Account account) {
				return account.getEmail();
			}
		};
		
		TextColumn<Account> typeColumn = new TextColumn<Account>() {

			@Override
			public String getValue(Account account) {
				return account.getUtente().toString();
			}
		};
		
		Column<Account, Account> editColumn = new Column<Account, Account>(
				new ActionCell<Account>("Modifica", new Delegate<Account>() {
					
					@Override
					public void execute(Account account) {
						showEditingPanel(account);
					}
				})
			){
			@Override
			public Account getValue(Account account) {
				return account;
			}
		};
		
		ctFind.addColumn(nameColumn,"Nome");
		ctFind.addColumn(surnameColumn, "Cognome");
		ctFind.addColumn(emailColumn, "Email");
		ctFind.addColumn(typeColumn, "Categoria");
		ctFind.addColumn(editColumn);
			
		surnameColumn.setSortable(true);
		nameColumn.setSortable(true);
		emailColumn.setSortable(true);
		
		gs.getAllAccounts(new AsyncCallback<ArrayList<Account>>() {
			
			@Override
			public void onFailure(Throwable caught) {
				Window.alert("Errore connessione server");
			}

			@Override
			public void onSuccess(ArrayList<Account> result) {
				
				dataProvider.getList().clear();
				// rimuove admin dalla visualizzazione
				int i = 0;
				while(i <= result.size()) {
					if(result.get(i).getEmail() == "admin") {
						result.remove(i);
						break;
					}
					i++;
				}
				dataProvider.getList().addAll(result);
				
				Comparator<Account> comparator = new Comparator<Account>() {

					@Override
					public int compare(Account o1, Account o2) {
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
				
				columnSortHandler.setComparator(nameColumn,comparator);
				ctFind.addColumnSortHandler(columnSortHandler);
				ctFind.getColumnSortList().push(nameColumn);
				ctFind.redraw();

			}
		});
		tablePanel.add(ctFind,   DockPanel.CENTER);
		tablePanel.add(btnLista, DockPanel.EAST);
		tablePanel.add(pager,    DockPanel.SOUTH);
	}
}
