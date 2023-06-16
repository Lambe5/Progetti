package com.google.gwt.ProgettoIngegneria.client;

import java.util.ArrayList;

import com.google.gwt.user.client.rpc.AsyncCallback;

public interface GreetingServiceAsync {
	
	void checkPassword(String email, String password, AsyncCallback<Boolean> callback) throws IllegalArgumentException;

	void getInfo(String email, AsyncCallback<Account> callback);

	void registraAmministratore(AsyncCallback<Void> callback);

	void createAccount(Account account, AsyncCallback<String> callback);
	
	void createCorso(Corso corso, AsyncCallback<Void> callback);

	void createEsame(Esame esame, AsyncCallback<Void> callback);

	void getEsame(Corso corso, AsyncCallback<Esame> callback);
	
	void getAllAccounts(AsyncCallback<ArrayList<Account>> callback);
	
	void getAllCorsi(AsyncCallback<ArrayList<Corso>> callback);
	
	void getAllEsami(AsyncCallback<ArrayList<Esame>> callback);
	
	void editAccount(Account editedAccount, AsyncCallback<Void> callback);
	
	void iscrizioneCorso(String nomeCorso, String emailStudente, AsyncCallback<Void> callback);
	
	void disiscrizioneCorso(String nomeCorso, String emailStudente, AsyncCallback<Void> callback);
	
	void iscrizioneEsame(String nomeEsame, String emailStudente, AsyncCallback<Void> callback);
	
	void disiscrizioneEsame(String nomeEsame, String emailStudente, AsyncCallback<Void> callback);

	void editCorso(Corso editedCorso, AsyncCallback<Void> callback);

	void editEsame(Esame editedEsame, AsyncCallback<Void> callback);

	void deleteCorso(Corso corso, AsyncCallback<Void> callback);
	
	void deleteEsame(Esame esame, AsyncCallback<Void> callback);

	void getCorso(String nome, AsyncCallback<Corso> callback);
	
	void getIscrittiCorso(String corso, AsyncCallback<ArrayList<String>> callback);
	
	void getIscrittiEsame(String esame, AsyncCallback<ArrayList<String>> callback);
	
	void getMyCorsi(String email, AsyncCallback<ArrayList<String>> callback);
	
	void getMyEsami(String email, AsyncCallback<ArrayList<String>> callback);

	void getProfessors(AsyncCallback<ArrayList<Account>> callback);

	void saveGradesProfessorSide(String nomeCorso , ArrayList<Voto> students_Grades, AsyncCallback<Void> callback);

	void saveGradesSegSide(String nomeCorso, AsyncCallback<Void> callback);

	void getAllStudents(AsyncCallback<ArrayList<Account>> callback);

	void getGradesFromStudEmail(String email, AsyncCallback<ArrayList<Voto>> callback);

	void getGradesFromCorso(String nomeCorso, AsyncCallback<ArrayList<Voto>> callback);

	void getCorsiconVotidaPubblicare(AsyncCallback<ArrayList<String>> callback);

}
