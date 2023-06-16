package com.google.gwt.ProgettoIngegneria.client;

import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.event.dom.client.KeyCodes;
import com.google.gwt.event.dom.client.KeyUpEvent;
import com.google.gwt.event.dom.client.KeyUpHandler;
import com.google.gwt.user.client.Window;
import com.google.gwt.user.client.rpc.AsyncCallback;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.FlexTable;
import com.google.gwt.user.client.ui.Label;
import com.google.gwt.user.client.ui.PasswordTextBox;
import com.google.gwt.user.client.ui.TextBox;
import com.google.gwt.user.client.ui.VerticalPanel;

public class LogPage extends Composite {
	
	// devono stare fuori senno' non posso chiamarli
	static Button 		   logButton;
	static TextBox 		   emailLogField;
	static PasswordTextBox pwField;
	static Label 		   errorLabel;
	
	private final static GreetingServiceAsync greetingService = GWT.create(GreetingService.class);

	private final String      adminPageString = "AdminPage";
	private final String    studentPageString = "StudentPage";
	private final String  professorPageString = "ProfessorPage";
	private final String segreteriaPageString = "SegreteriaPage";
	
	//creazione pagina di login
	public LogPage() {
		
		logButton     = new Button("Log in");
 		emailLogField = new TextBox();
 		pwField 	  = new PasswordTextBox();
 		errorLabel    = new Label();
		
        VerticalPanel mainPanel = new VerticalPanel();
        FlexTable  	   logPanel = new FlexTable();

		

		logButton.addClickHandler(new ClickHandler() {
			
			@Override
			public void onClick(ClickEvent event) {
				sendInfoForLogin();
				
			}
		});

		pwField.addKeyUpHandler(new KeyUpHandler() {
			
			@Override
			public void onKeyUp(KeyUpEvent event) {
				
				if (event.getNativeKeyCode() == KeyCodes.KEY_ENTER) {
					if(event.getSource() == LogPage.pwField) {
						sendInfoForLogin();
					}
					
				}
			}
		});

		// Assemble logpage panel
		logPanel.setText(   0, 0, "Accedi");
		logPanel.setText(  10, 0, "Email:");
		logPanel.setWidget(11, 0, emailLogField);
		logPanel.setText(  12, 0, "Password:");
		logPanel.setWidget(13, 0, pwField);
		logPanel.setWidget(14, 0, logButton);
		logPanel.setWidget(15, 0, errorLabel);

		errorLabel.setVisible(false);

		mainPanel.add(logPanel);
		initWidget(mainPanel);


		//Delego gli stili al CSS, qui definisco il nome dello stile degli elementi che andro' a modificare nel CSS		
		logPanel.setStylePrimaryName("loginPanel");
		pwField.setStylePrimaryName("loginpanel-pwdField");
		logButton.setStylePrimaryName("loginpanel-logButton");
		emailLogField.setStylePrimaryName("loginpanel-emailLogField");
	}
	
	private  void setErrorLabel(String text) {
		errorLabel.setText(text);
		errorLabel.setVisible(true);
	}

	private  void clearLogFields() {
		emailLogField.setText("");
		pwField.setText("");
	}
	// chiede al server se la password e'corretta
	private void sendInfoForLogin() {

			String emailToServer = emailLogField.getText();
			String pwToServer = pwField.getText();
			
			greetingService.checkPassword(emailToServer, pwToServer, new AsyncCallback<Boolean>() {

				@Override
				public void onFailure(Throwable caught) {
					Window.alert("Errore connessione server");	
				}

				@Override
				public void onSuccess(Boolean result) {
					if (result) {

						/*faccio una chiamata per ottenere l'account e vedere se e' studente o professore*/
						greetingService.getInfo(emailToServer, new AsyncCallback<Account>() {


							@Override
							public void onFailure(Throwable caught) {
								Window.alert("Errore connessione server");
							}

							@Override
							public void onSuccess(Account account) {
								
								switch (account.getUtente()) {
								case STUDENTE:
									ControllerPages.showThisPage(studentPageString, emailToServer);
									break;
								case DOCENTE:
									ControllerPages.showThisPage(professorPageString, emailToServer);
									break;
								case SEGRETERIA:
									ControllerPages.showThisPage(segreteriaPageString, emailToServer);
									break;
								case AMMINISTRATORE:
									ControllerPages.showThisPage(adminPageString, emailToServer);
									break;
								}
							}
						});

					} else {
						setErrorLabel("email o password errate");
					}
					clearLogFields();
				}
			});
	}
}