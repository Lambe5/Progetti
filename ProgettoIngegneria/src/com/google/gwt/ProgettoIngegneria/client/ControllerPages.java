package com.google.gwt.ProgettoIngegneria.client;
import com.google.gwt.user.client.ui.RootPanel;

public class ControllerPages {
	//Controller per scegliere la pagina da mostrare
	public static void showThisPage(String page, String emailAccount) {
		switch (page) {
		case "HomePage":
			showHomePage();
			break;
		case "LogPage":
			showLogPage();
			break;
		case "AdminPage":
			showAdminPage();
			break;
		case "StudentPage":
			showStudentPage(emailAccount);
			break;
		case "ProfessorPage":
			showProfessorPage(emailAccount);
			break;
		case "SegreteriaPage":
			showSegreteriaPage("");
			break;
		}
	}
	
	private static void showHomePage() {
		
		RootPanel.get("homepage").clear();
		HomePage homePage = new HomePage();
		RootPanel.get("homepage").add(homePage);
	}
	
	private static void showLogPage() {
		
		RootPanel.get("homepage").clear();
		LogPage logPage = new LogPage();
		RootPanel.get("homepage").add(logPage);
	}
	
	private static void showAdminPage() {
		
		RootPanel.get("homepage").clear();
		AdminPage adminPage = new AdminPage();
		RootPanel.get("homepage").add(adminPage);
	}
	
	private static void showStudentPage(String emailAccount) {
		
		RootPanel.get("homepage").clear();
		StudentPage studentPage = new StudentPage(emailAccount);
		RootPanel.get("homepage").add(studentPage);
	}
	
	private static void showProfessorPage(String emailAccount) {
		
		RootPanel.get("homepage").clear();
		ProfessorPage professorPage = new ProfessorPage(emailAccount);
		RootPanel.get("homepage").add(professorPage);
	}
	
	private static void showSegreteriaPage(String emailAccount) {
		
		RootPanel.get("homepage").clear();

		SegreteriaPage segreteriaPage = new SegreteriaPage();
		RootPanel.get("homepage").add(segreteriaPage);
	}
}
