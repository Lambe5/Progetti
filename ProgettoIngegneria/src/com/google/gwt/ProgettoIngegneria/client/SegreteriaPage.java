package com.google.gwt.ProgettoIngegneria.client;

import java.util.ArrayList;
import java.util.Comparator;

import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.user.cellview.client.CellTable;
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
import com.google.gwt.user.client.ui.MenuBar;
import com.google.gwt.user.client.ui.TextBox;
import com.google.gwt.user.client.ui.VerticalPanel;
import com.google.gwt.view.client.ListDataProvider;
import com.google.gwt.view.client.ProvidesKey;


public class SegreteriaPage extends Composite{
	
	private final String homePageString="HomePage";
	private final static GreetingServiceAsync gs = GWT.create(GreetingService.class);
	
	VerticalPanel centerPanel;
	DockPanel 	  mainPanel;
	DockPanel     tablePanel;
	
	public SegreteriaPage() {
		
		mainPanel = new DockPanel();
		tablePanel= new DockPanel();
		centerPanel = new VerticalPanel();
		
		mainPanel.setSpacing(10);
		mainPanel.add(centerPanel,DockPanel.CENTER);
		mainPanel.add(tablePanel,DockPanel.EAST);
		
		Button btnLogOut = new Button("Log out");
		btnLogOut.addClickHandler(new ClickHandler() {
			
			@Override
			public void onClick(ClickEvent event) {
				ControllerPages.showThisPage(homePageString, "");
			}
		});
		mainPanel.add(btnLogOut, DockPanel.NORTH);
		
	    showMenuPanel();
	    showStudentsTable();
	    
		initWidget(mainPanel);
	}
	
	private void showStudentsTable() {
		
		tablePanel.clear();
		
		ListDataProvider<Account> dataProvider = new ListDataProvider<>();
		ListHandler<Account> columnSortHandler = new ListHandler<>(dataProvider.getList());
		
		
		ProvidesKey<Account> keyProvider = new ProvidesKey<Account>() {

			@Override
			public Object getKey(Account item) {
				return (item == null) ? null : item.id;
			}
		};
		
		CellTable<Account> ctFind = new CellTable<>(keyProvider);
		
		TextColumn<Account> emailColumn = new TextColumn<Account>() {
			@Override
			public String getValue(Account account) {
				return account.getEmail();
			}
		};
	
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

		SimplePager.Resources pagerResources = GWT.create(SimplePager.Resources.class);
	    SimplePager pager = new SimplePager(TextLocation.CENTER, pagerResources, false, 0, true);
	    pager.setDisplay(ctFind);
	    
		dataProvider.addDataDisplay(ctFind);
		
		nameColumn.setSortable(true);
		surnameColumn.setSortable(true);
		
		gs.getAllStudents(new AsyncCallback<ArrayList<Account>>() {
			
			@Override
			public void onSuccess(ArrayList<Account> listStudents) {
				
				dataProvider.getList().clear();
				dataProvider.getList().addAll(listStudents);
				
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
				columnSortHandler.setComparator(nameColumn, comparator);
				ctFind.addColumnSortHandler(columnSortHandler);
				ctFind.getColumnSortList().push(nameColumn);
				ctFind.redraw();
			}
			
			@Override
			public void onFailure(Throwable caught) {
				Window.alert("Errore connessione server");
			}
		});
		
		ctFind.addColumn(nameColumn,"Nome");
		ctFind.addColumn(surnameColumn, "Cognome");
		ctFind.addColumn(emailColumn, "E-mail");
		
	   // SelectionModel<Account> selectionModel = new SingleSelectionModel<Account>(keyProvider);
	   // ctFind.setSelectionModel(selectionModel);
		tablePanel.add(ctFind,DockPanel.CENTER);
		tablePanel.add(pager,DockPanel.SOUTH);
	}
	
	private void showMenuPanel() {
		
		MenuBar menuPanel = new MenuBar(true);
		
		
		menuPanel.addItem("Cerca Studente esistente", new Command() {
			
			@Override
			public void execute() {
				showFindStudent();
			}
		});
		
		menuPanel.addSeparator();
		
		menuPanel.addItem("Pubblica i Voti", new Command() {
			
			@Override
			public void execute() {
				showCorsi();
			}
		});
		
		mainPanel.add(menuPanel,DockPanel.WEST);
		
	}
	private void showFindStudent() {
		
		centerPanel.clear();
		
		FlexTable findPanel = new FlexTable();
		TextBox tbFindEmail = new TextBox();
		Button btnFind = new Button("Cerca");
		
		btnFind.addClickHandler(new ClickHandler() {
			
			@Override
			public void onClick(ClickEvent event) {
				gs.getInfo(tbFindEmail.getText(), new AsyncCallback<Account>() {

					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Errore connessione server");						
						
					}

					@Override
					public void onSuccess(Account studente) {
						if(studente == null || studente.getUtente() != TipoUtente.STUDENTE) {
							findPanel.setText(5, 0, "Lo studente cercato non esiste");
							findPanel.setText(5, 1, "");
							findPanel.setText(6, 0, "");
							findPanel.setText(6, 1, "");
							findPanel.setText(7, 0, "");
							findPanel.setText(7, 1, "");
							findPanel.setText(8, 0, "");
							findPanel.setText(8, 1, "");
							
						}
						else if(studente.getUtente() == TipoUtente.STUDENTE) {
							findPanel.setText(5, 0, "Nome:");
							findPanel.setText(5, 1, studente.getNome());
							findPanel.setText(6, 0, "Cognome:");
							findPanel.setText(6, 1, studente.getCognome());
							findPanel.setText(7, 0, "Email:");
							findPanel.setText(7, 1, studente.getEmail());
						}
					}
				});
			}
		});
		findPanel.setText(0, 0, "Email");
		findPanel.setWidget(0, 1, tbFindEmail);
		findPanel.setWidget(1, 0, btnFind);

		centerPanel.add(findPanel);
	}
	private void showCorsi() {

		centerPanel.clear();
		tablePanel.clear();
		MenuBar menuCorsi= new MenuBar(true);
		
		gs.getCorsiconVotidaPubblicare(new AsyncCallback<ArrayList<String>>() {
			
			@Override
			public void onSuccess(ArrayList<String> listCourses) {
				
				if(listCourses.size() == 0) {
					FlexTable panelError= new FlexTable();
					Button btnBack= new Button("Indietro");
					
					btnBack.addClickHandler(new ClickHandler() {
						
						@Override
						public void onClick(ClickEvent event) {
							centerPanel.clear();
							showStudentsTable();
						}
					});
					
					panelError.setText(0, 0, "Non ci sono voti da pubblicare");
					panelError.setWidget(1,0 , btnBack);
					
					centerPanel.add(panelError);
				}
				for(int i=0; i < listCourses.size(); i++) {
					
					if(i != 0)
						menuCorsi.addSeparator();
					
					String courseName= listCourses.get(i);
					
					menuCorsi.addItem(courseName, new Command() {
						@Override
						public void execute() {
							showVoti(courseName);
						}
					});
					
					
				}
				
				centerPanel.add(menuCorsi);
			}
			
			@Override
			public void onFailure(Throwable caught) {
			Window.alert("Errore connessione Server");
			}
		});
		
	}
	private void showVoti(String courseName) {
		
		centerPanel.clear();
		
		DockPanel tablePanel = new DockPanel();
		
		ListDataProvider<Voto> dataProvider = new ListDataProvider<>();
		CellTable<Voto> ctVoti= new CellTable<Voto>(dataProvider);
		
		Button btnPubblicaVoti= new Button("Pubblica");
		
		SimplePager.Resources pagerResources = GWT.create(SimplePager.Resources.class);
	    SimplePager pager = new SimplePager(TextLocation.CENTER, pagerResources, false, 0, true);
	    pager.setDisplay(ctVoti);
	    
		dataProvider.addDataDisplay(ctVoti);
		
		TextColumn<Voto> emailColumn= new TextColumn<Voto>() {
			
			@Override
			public String getValue(Voto voto) {
				return voto.getStudente();
			}
		};
		TextColumn<Voto> gradeColumn = new TextColumn<Voto>() {
			
			@Override
			public String getValue(Voto voto) {
				return voto.getVoto();
			}
		};
		
		
		ctVoti.addColumn(emailColumn,"E-mail");
		ctVoti.addColumn(gradeColumn,"Voto");
		
		btnPubblicaVoti.addClickHandler(new ClickHandler() {
			
			@Override
			public void onClick(ClickEvent event) {
				gs.saveGradesSegSide(courseName,new AsyncCallback<Void>() {
					
					@Override
					public void onSuccess(Void result) {
						showCorsi();
					}
					
					@Override
					public void onFailure(Throwable caught) {
						Window.alert("Errore Connessione Server");
						
					}
				});
			}
		});
		
		gs.getGradesFromCorso(courseName, new AsyncCallback<ArrayList<Voto>>() {
			
			@Override
			public void onSuccess(ArrayList<Voto>  listGrades) {
				
				dataProvider.getList().clear();
				dataProvider.getList().addAll(listGrades);
				
			}
			@Override
			public void onFailure(Throwable caught) {
				Window.alert("Errore connessione Server");	
			}
		});
		
		tablePanel.add(ctVoti,DockPanel.CENTER);
		tablePanel.add(pager,DockPanel.SOUTH);
		tablePanel.add(btnPubblicaVoti,DockPanel.EAST);
		
		centerPanel.add(tablePanel);
	}
}