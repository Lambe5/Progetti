package com.google.gwt.ProgettoIngegneria.server;

import java.io.File;
import java.io.Serializable;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.Set;

import javax.servlet.ServletContext;
import org.mapdb.DB;
import org.mapdb.DBMaker;
import org.mapdb.HTreeMap;
import com.google.gwt.ProgettoIngegneria.client.Account;
import com.google.gwt.ProgettoIngegneria.client.Amministratore;
import com.google.gwt.ProgettoIngegneria.client.Corso;
import com.google.gwt.ProgettoIngegneria.client.Esame;
import com.google.gwt.ProgettoIngegneria.client.FactoryCreaAmministratore;
import com.google.gwt.ProgettoIngegneria.client.GreetingService;
import com.google.gwt.ProgettoIngegneria.client.TipoUtente;
import com.google.gwt.ProgettoIngegneria.client.Voto;
import com.google.gwt.user.server.rpc.RemoteServiceServlet;

@SuppressWarnings("serial")
public class GreetingServiceImpl extends RemoteServiceServlet implements GreetingService, Serializable {

	private DB getDB() {

		ServletContext context = this.getServletContext();
		synchronized (context) {
			DB db = (DB)context.getAttribute("DB");
			if(db == null) {
				db = DBMaker.newFileDB(new File("db")).closeOnJvmShutdown().make();
				context.setAttribute("DB", db);
			}
			return db;
		}	
	}

	private HTreeMap<String, Account> getAccountMap() {
		DB db = getDB();
		HTreeMap<String, Account> map = db.getHashMap("listAccount");
		return map;
	}

	private HTreeMap<String, Corso> getCorsiMap() {
		DB db = getDB();
		HTreeMap<String, Corso> map = db.getHashMap("listaCorsi");
		return map;
	}

	private HTreeMap<String, Esame> getEsamiMap(){
		DB db = getDB();
		HTreeMap<String, Esame> map = db.getHashMap("listaEsami");
		return map;
	}

	// mappa per iscritti corso
	// quando si usa: quando creo il corso, quando qualcuno si iscrive/disiscrive, quando il corso viene eliminato
	private HTreeMap<String, ArrayList<String>> getIscrittiCorsoMap(){
		DB db = getDB();
		HTreeMap<String, ArrayList<String>> map = db.getHashMap("iscrizioniCorsi");
		return map;
	}

	// iscritti esame
	private HTreeMap<String, ArrayList<String>> getIscrittiEsameMap(){
		DB db = getDB();
		HTreeMap<String, ArrayList<String>> map = db.getHashMap("iscrizioniEsami");
		return map;
	}

	// corsi per ogni account
	private HTreeMap<String, ArrayList<String>> getMyCorsiMap(){
		DB db = getDB();
		HTreeMap<String, ArrayList<String>> map = db.getHashMap("myCorsi");
		return map;
	}

	//Ottiene esami per ogni account
	private HTreeMap<String, ArrayList<String>> getMyEsamiMap(){
		DB db = getDB();
		HTreeMap<String, ArrayList<String>> map = db.getHashMap("myEsami");
		return map;
	}

	// get dei voti associati all'esame 
	private HTreeMap<String, ArrayList<Voto>> getGradesFromExamMap(){
		DB db= getDB();
		HTreeMap<String, ArrayList<Voto>> map= db.getHashMap("GradesFromExam");
		return map;
	}

	//get dei voti associati allo studente (String == studentEmail)
	private HTreeMap<String, ArrayList<Voto>> getGradesFromStudEmailMap(){
		DB db=getDB();
		HTreeMap<String, ArrayList<Voto>> map = db.getHashMap("GradesFromEmail");
		return map;
	}

	private void deleteMap(String map) {
		DB db = getDB();
		db.delete(map);
		db.commit();
	}

	private void dbCommit() {
		DB db = getDB();
		db.commit();
	}

	@Override
	public boolean checkPassword(String email, String password) throws IllegalArgumentException{

		HTreeMap<String, Account> checkMap = getAccountMap();

		// email corretta
		if(checkMap.get(email) != null && checkMap.get(email).getPassword().equals(password)){

			dbCommit();
			return true;
		} else
			// password sbagliata
			dbCommit();
		return false;
	}

	@Override
	public String createAccount(Account account) {

		HTreeMap<String, Account> map = getAccountMap();

		if(map.get(account.getEmail()) != null) {
			account.setEmail(increaseIndexInEmail(account.getEmail()));
		}

		map.put(account.getEmail(), account);

		// creo una mappa per i corsi e gli esami
		if(!account.getUtente().equals(TipoUtente.SEGRETERIA)) {
			HTreeMap<String, ArrayList<String>> corsiMap = getMyCorsiMap();
			corsiMap.put(account.getEmail(), new ArrayList<String>());
			if(account.getUtente().equals(TipoUtente.STUDENTE)) {
				HTreeMap<String, ArrayList<String>> esamiMap = getMyEsamiMap();
				esamiMap.put(account.getEmail(), new ArrayList<String>());
			}
		}
		dbCommit();

		return account.getEmail();
	}

	@Override
	public void editAccount(Account editedAccount) {
		HTreeMap<String, Account> map = getAccountMap();
		// salva le modifiche
		map.replace(editedAccount.getEmail(), editedAccount);
		dbCommit();
	}

	//metodo per aggiungere un corso al db
	@Override
	public void createCorso(Corso corso) {
		HTreeMap<String, Corso> map = getCorsiMap();

		//se il corso non esiste gia' lo inserisco
		if(map.get(corso.getNome()) == null) {

			map.put(corso.getNome(), corso);

			// aggiunge corso a lista corsi del docente
			HTreeMap<String, ArrayList<String>> myCorsiMap = getMyCorsiMap();
			ArrayList<String> listaCorsi = myCorsiMap.get(corso.getEmailDocente());
			listaCorsi.add(corso.getNome());
			myCorsiMap.replace(corso.getEmailDocente(), listaCorsi);

			// crea voce in mappa iscritti corso
			HTreeMap<String, ArrayList<String>> iscrittiMap = getIscrittiCorsoMap();
			iscrittiMap.put(corso.getNome(), new ArrayList<>());

			dbCommit();
		}
	}

	//metodo per aggiungere un esame al db
	@Override
	public void createEsame(Esame esame) {
		HTreeMap<String, Esame> map = getEsamiMap();

		//se l'esame non esiste gia' lo inserisco
		if(map.get(esame.getNomeCorsoRif()) == null) {

			map.put(esame.getNomeCorsoRif(), esame);

			// crea voce in mappa iscritti corso
			HTreeMap<String, ArrayList<String>> iscrittiMap = getIscrittiEsameMap();
			iscrittiMap.put(esame.getNomeCorsoRif(), new ArrayList<>());

			dbCommit();
		}
	}

	@Override
	public ArrayList<String> getIscrittiCorso(String corso) {
		HTreeMap<String, ArrayList<String>> map = getIscrittiCorsoMap();
		return map.get(corso);
	}

	@Override
	public ArrayList<String> getIscrittiEsame(String esame) {
		HTreeMap<String, ArrayList<String>> map = getIscrittiEsameMap();
		return map.get(esame);
	}
	@Override
	//Ottenere i voti relativi all'email di uno studente
	public ArrayList<Voto> getGradesFromStudEmail(String email){
		HTreeMap<String, ArrayList<Voto>> map = getGradesFromStudEmailMap();
		return map.get(email);
	}
	@Override
	//Ottenere i voti relativi al corso 
	public ArrayList<Voto> getGradesFromCorso(String nomeCorso){
		HTreeMap<String, ArrayList<Voto>> map = getGradesFromExamMap();
		return map.get(nomeCorso);
	}

	@Override
	public void iscrizioneCorso(String nomeCorso, String emailStudente) {

		// aggiunge studente a lista studenti del corso
		HTreeMap<String, ArrayList<String>> corsoMap = getIscrittiCorsoMap();
		ArrayList<String> newListStudents = corsoMap.get(nomeCorso);
		newListStudents.add(emailStudente);
		corsoMap.replace(nomeCorso, newListStudents);

		// aggiungo il corso ai corsi a cui lo studente e' iscritto
		HTreeMap<String, ArrayList<String>> studentMap = getMyCorsiMap();
		ArrayList<String> newListCorsi = studentMap.get(emailStudente);
		newListCorsi.add(nomeCorso);
		studentMap.replace(emailStudente, newListCorsi);

		dbCommit();
	}

	@Override
	public void disiscrizioneCorso(String nomeCorso, String emailStudente) {

		// rimuove studente da lista studenti del corso
		HTreeMap<String, ArrayList<String>> corsoMap = getIscrittiCorsoMap();
		ArrayList<String> newListStudents = corsoMap.get(nomeCorso);
		newListStudents.remove(emailStudente);
		corsoMap.replace(nomeCorso, newListStudents);

		// rimuove il corso dalla lista corsi dello studente
		HTreeMap<String, ArrayList<String>> studentMap = getMyCorsiMap();
		ArrayList<String> newListCorsi = studentMap.get(emailStudente);
		newListCorsi.remove(nomeCorso);
		studentMap.replace(emailStudente, newListCorsi);

		// rimuovo anche eventuale esame
		disiscrizioneEsame(nomeCorso, emailStudente);

		dbCommit();
	}

	@Override
	public void iscrizioneEsame(String nomeEsame, String emailStudente) {

		// aggiunge studente a lista studenti dell'esame
		HTreeMap<String, ArrayList<String>> esameMap = getIscrittiEsameMap();
		ArrayList<String> newListStudents = esameMap.get(nomeEsame);
		newListStudents.add(emailStudente);
		esameMap.replace(nomeEsame, newListStudents);

		// aggiungo l'esame agli esami a cui lo studente e' iscritto
		HTreeMap<String, ArrayList<String>> studentMap = getMyEsamiMap();
		ArrayList<String> newListEsami = studentMap.get(emailStudente);
		newListEsami.add(nomeEsame);
		studentMap.replace(emailStudente, newListEsami);

		dbCommit();
	}

	@Override
	public void disiscrizioneEsame(String nomeEsame, String emailStudente) {

		// rimuove studente da lista studenti dell'esame
		HTreeMap<String, ArrayList<String>> esameMap = getIscrittiEsameMap();
		ArrayList<String> newListStudents = esameMap.get(nomeEsame);
		newListStudents.remove(emailStudente);
		esameMap.replace(nomeEsame, newListStudents);

		// rimuove l'esame dalla lista esami dello studente
		HTreeMap<String, ArrayList<String>> studentMap = getMyEsamiMap();
		ArrayList<String> newListEsami = studentMap.get(emailStudente);
		newListEsami.remove(nomeEsame);
		studentMap.replace(emailStudente, newListEsami);

		dbCommit();
	}

	@Override
	public ArrayList<String> getMyCorsi(String email) {

		HTreeMap<String, ArrayList<String>> nomeCorsiMap = getMyCorsiMap();

		// controllo: se il corso e' stato eliminato lo rimuovo
		ArrayList<String> listaCorsi = new ArrayList<>();
		listaCorsi = nomeCorsiMap.get(email);
		HTreeMap<String, Corso> corsiMap = getCorsiMap();
		for(int i = 0; i < listaCorsi.size(); i++) {
			if(!corsiMap.containsKey(listaCorsi.get(i))) {
				listaCorsi.remove(listaCorsi.get(i));
				i--;
			}
		}
		nomeCorsiMap = getMyCorsiMap();
		nomeCorsiMap.replace(email,listaCorsi);
		dbCommit();

		return nomeCorsiMap.get(email);
	}

	@Override
	public ArrayList<String> getMyEsami(String email) {

		HTreeMap<String, ArrayList<String>> nomeEsamiMap = getMyEsamiMap();

		// se un esame non esiste piu' lo rimuovo
		ArrayList<String> listaEsami = nomeEsamiMap.get(email);
		HTreeMap<String, Esame> esamiMap = getEsamiMap();
		for(int i = 0; i < listaEsami.size(); i++) {
			if(!esamiMap.containsKey(listaEsami.get(i))) {
				listaEsami.remove(listaEsami.get(i));
				i--;
			}
		}
		nomeEsamiMap.replace(email, listaEsami);
		dbCommit();

		return nomeEsamiMap.get(email);
	}

	//faccio la ricerca per email 
	@Override
	public Account getInfo(String email) {
		HTreeMap<String, Account> accountMap = getAccountMap();
		return accountMap.get(email);
	}


	//registro l'amministratore al db
	@Override
	public void registraAmministratore() {
		FactoryCreaAmministratore factoryCreaAmm= new FactoryCreaAmministratore();
		Account admin = factoryCreaAmm.creaAdmin();
		HTreeMap<String, Account> accountMap = getAccountMap();

		admin.setPassword("admin");
		admin.setEmail("admin");

		if(accountMap.get(admin.getEmail()) == null) {
			accountMap.put(admin.getEmail(),
					admin);
		} else {
			accountMap.replace(admin.getEmail(), admin);
		}
		dbCommit();
	}

	@Override
	public ArrayList<Account> getAllAccounts() {
		HTreeMap<String, Account> map = getAccountMap();
		Set<String> accountsKeySet = map.keySet();
		Iterator<String> iterator = accountsKeySet.iterator();
		ArrayList<Account> accountsList = new ArrayList<>();
		while(iterator.hasNext()) {
			String nextKey = iterator.next();
			if(nextKey != null && nextKey != "")
				accountsList.add(map.get(nextKey));
		}
		return accountsList;
	}

	public ArrayList<Account> getAllStudents() {

		HTreeMap<String, Account> map = getAccountMap();
		Set<String> accountsKeySet = map.keySet();
		Iterator<String> iterator = accountsKeySet.iterator();
		ArrayList<Account> studentList = new ArrayList<>();
		while(iterator.hasNext()) {
			String nextKey = iterator.next();
			if(nextKey != null && nextKey != "" && map.get(nextKey).getUtente() == TipoUtente.STUDENTE)
				studentList.add(map.get(nextKey));
		}
		return studentList;
	}

	@Override
	public ArrayList<Corso> getAllCorsi() {

		HTreeMap<String, Corso> map = getCorsiMap();
		Set<String> classKeySet = map.keySet();
		Iterator<String> iterator = classKeySet.iterator();
		ArrayList<Corso> classList = new ArrayList<>();
		while (iterator.hasNext()) {
			String nextKey = iterator.next();
			if(nextKey != null && nextKey != "") {
				classList.add(map.get(nextKey));
			}
		}
		return classList;
	}

	@Override
	public Corso getCorso(String nome) {

		HTreeMap<String, Corso> map = getCorsiMap();
		return map.get(nome);
	}
	@Override
	public ArrayList<String> getCorsiconVotidaPubblicare(){

		HTreeMap<String, ArrayList<Voto>> map= getGradesFromExamMap();
		ArrayList<String> listaCorsi= new ArrayList<String>();
		Set<String> corsiKeySet = map.keySet();

		//listaCorsi.addAll(corsiKeySet);
		Iterator<String> iterator = corsiKeySet.iterator();

		while(iterator.hasNext()) {
			String nextKey= iterator.next();
			if(nextKey != null && nextKey != "") {
				listaCorsi.add(nextKey);
			}
		}

		return listaCorsi;
	}

	@Override
	public ArrayList<Esame> getAllEsami() {

		HTreeMap<String, Esame> map = getEsamiMap();
		Set<String> examKeySet = map.keySet();
		Iterator<String> iterator = examKeySet.iterator();
		ArrayList<Esame> examList = new ArrayList<>();
		while (iterator.hasNext()) {
			String nextKey = iterator.next();
			if(nextKey != null && nextKey != "") {
				examList.add(map.get(nextKey));
			}
		}
		return examList;
	}

	@Override
	public Esame getEsame(Corso corso) {

		HTreeMap<String, Esame> map = getEsamiMap();
		return map.get(corso.getNome());
	}

	@Override
	public void editCorso(Corso editedCorso) {

		HTreeMap<String, Corso> map = getCorsiMap();

		// salva le modifiche
		map.replace(editedCorso.getNome(), editedCorso);
		dbCommit();
	}

	@Override
	public void editEsame(Esame editedEsame) {

		HTreeMap<String, Esame> map = getEsamiMap();
		// salva le modifiche
		map.replace(editedEsame.getNomeCorsoRif(), editedEsame);
		dbCommit();
	}

	@Override
	public void deleteCorso(Corso corso) {

		// se esiste un esame associato lo elimina
		if(getEsame(corso) != null) {
			deleteEsame(getEsame(corso));
		}

		// elimino la mappa delle iscrizioni del corso
		deleteMap(corso.getNome());

		// rimuovo corso da lista corsi docente
		HTreeMap<String, ArrayList<String>> listaCorsiMap = getMyCorsiMap();
		ArrayList<String> newListaCorsiDocente = listaCorsiMap.get(corso.getEmailDocente());
		newListaCorsiDocente.remove(corso.getNome());
		listaCorsiMap.replace(corso.getEmailDocente(), newListaCorsiDocente);

		// rimuovo il corso da lista tutti i corsi
		HTreeMap<String, Corso> map = getCorsiMap();
		map.remove(corso.getNome());


		dbCommit();
	}

	@Override
	public void deleteEsame(Esame esame) {
		HTreeMap<String, Esame> map = getEsamiMap();
		map.remove(esame.getNomeCorsoRif());

		// elimino la mappa delle iscrizioni all'esame
		deleteMap(esame.getNomeCorsoRif());

		dbCommit();
	}

	@Override
	public ArrayList<Account> getProfessors() {

		ArrayList<Account> listaAccount = getAllAccounts();

		for(int i = 0; i < listaAccount.size(); i++) {
			if(listaAccount.get(i).getUtente() != TipoUtente.DOCENTE) {
				listaAccount.remove(i);
				i--;
			}
		}
		return listaAccount;
	}
	
	private String increaseIndexInEmail(String email) {

		// controlla se esiste la mail
		boolean exists = true;
		String updatedEmail = email;
		while(exists) {
			if(getInfo(updatedEmail) == null) {
				exists = false;
			} else {
				int indexOfAt = updatedEmail.indexOf("@", 0);
				if(indexOfAt > 0) {
					String firstPart = updatedEmail.substring(0, indexOfAt);
					String secondPart = updatedEmail.substring(indexOfAt);
					int numbersInEmailInt;
					String firstPartNoNumber;

					String numbersInEmail = getLastNumber(firstPart);
					if(numbersInEmail != null) {
						// devo togliere dall'email i numeri
						firstPartNoNumber = firstPart.substring(0, firstPart.length() - numbersInEmail.length());

						numbersInEmailInt = Integer.parseInt(numbersInEmail);
						numbersInEmailInt = numbersInEmailInt + 1;
					} else {
						firstPartNoNumber = firstPart;
						numbersInEmailInt = 1;
					}
					updatedEmail = firstPartNoNumber + numbersInEmailInt + secondPart;
				}
			}
		}
		return updatedEmail;
	}

	public String getLastNumber(String emailPart) {
		String[] numbers = {"1","2","3","4","5","6","7","8","9","0"};
		String lastChar = emailPart.substring(emailPart.length()-1);
		String numbersInEmail = null;
		for(int i = 0; i < numbers.length; i++) {
			if(lastChar.equals(numbers[i])) {
				String a = getLastNumber(emailPart.substring(0, emailPart.length()-1));
				if(a!= null) {
					numbersInEmail = a + lastChar;
				} else numbersInEmail = lastChar;
			}
		}
		return numbersInEmail;
	}
	//salvo i voti nel DB lato professore (non pubblicati)
	@Override 
	public void saveGradesProfessorSide(String nomeCorso , ArrayList<Voto> students_Grades) {

		//Mappa in cui andr� a salvare i voti relativi per ogni esame
		HTreeMap<String,ArrayList<Voto>> mapSavedGrades = getGradesFromExamMap();



		mapSavedGrades.put(nomeCorso,students_Grades);

		dbCommit(); 

		ArrayList<String> studentsSubscribeExam = getIscrittiEsame(nomeCorso);

		while(studentsSubscribeExam.size() > 0) {

			disiscrizioneEsame(nomeCorso , studentsSubscribeExam.get(0));
		}

	}
	//salvo i voti nel DB lato segreteria (pubblicati)
	@Override
	public void saveGradesSegSide(String nomeCorso) {

		HTreeMap<String, ArrayList<Voto>> mapStudentsGrades = getGradesFromStudEmailMap();
		HTreeMap<String, ArrayList<Voto>> mapGradesFromExam= getGradesFromExamMap();

		ArrayList<Voto> gradesfromExams= mapGradesFromExam.get(nomeCorso);
		
		for(int i=0; i < gradesfromExams.size();i++ ) {
			
			String emailStud = gradesfromExams.get(i).getStudente();
			ArrayList<Voto> gradesForStudent = new ArrayList<Voto>();
			
			if(mapStudentsGrades.get(emailStud) != null ) {

			    gradesForStudent= mapStudentsGrades.get(emailStud);
				
			}
			
			gradesForStudent.add(gradesfromExams.get(i));

			mapStudentsGrades.put(emailStud, gradesForStudent);
		}
		mapGradesFromExam.remove(nomeCorso);

		dbCommit();

	}

}