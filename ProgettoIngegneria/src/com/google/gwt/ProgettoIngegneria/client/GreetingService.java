package com.google.gwt.ProgettoIngegneria.client;

import java.util.ArrayList;

import com.google.gwt.user.client.rpc.RemoteService;
import com.google.gwt.user.client.rpc.RemoteServiceRelativePath;

@RemoteServiceRelativePath("greet")
public interface GreetingService extends RemoteService {
	
	boolean checkPassword(String email, String password) throws IllegalArgumentException;
	
	Account getInfo(String email);
	
	void registraAmministratore();
	
	String createAccount(Account account);
	
	void editAccount(Account editedAccount);

	ArrayList<Account> getAllAccounts();

	ArrayList<Corso> getAllCorsi();
	
	Corso getCorso(String nome);
	
	ArrayList<Esame> getAllEsami();

	Esame getEsame(Corso corso);
	
	void createCorso(Corso corso);

	void createEsame(Esame esame);

	void iscrizioneCorso(String nomeCorso, String emailStudente);
	
	void editCorso(Corso editedCorso);

	void editEsame(Esame editedEsame);

	ArrayList<Account> getProfessors();

	void deleteCorso(Corso corso);
	
	void deleteEsame(Esame esame);

	ArrayList<String> getIscrittiCorso(String corso);

	ArrayList<String> getIscrittiEsame(String esame);

	ArrayList<String> getMyCorsi(String email);
	
	ArrayList<String> getMyEsami(String email);

	void disiscrizioneCorso(String nomeCorso, String emailStudente);

	void iscrizioneEsame(String nomeEsame, String emailStudente);

	void disiscrizioneEsame(String nomeEsame, String emailStudente);

	ArrayList<Account> getAllStudents();
  
	
   void saveGradesProfessorSide(String nomeCorso , ArrayList<Voto> students_Grades);

	void saveGradesSegSide(String nomeCorso);
  
	ArrayList<Voto> getGradesFromStudEmail(String email);
	ArrayList<Voto> getGradesFromCorso(String nomeCorso);

	ArrayList<String> getCorsiconVotidaPubblicare();
}
