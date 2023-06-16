# Progetto_Basi
Directory in _C:\xampp\htdocs\Progetto_Basi_  <br>
per connettersi alla piattaforma CONFVIRTUAL --> https://localhost/Progetto_Basi/Homepage.php/Homepage.php 

## Componenti del gruppo:
Riccardo Lambertini | riccardo.lambertini5@studio.unibo.it | 0000934793 <br>
Kevin Renda | kevin.renda@studio.unibo.it | 0000915714 <br>
Andrea Bianchi | andrea.bianchi26@studio.unibo.it | 0000948164 <br>


## Per far funzionare il progetto:

###  Installare estensione PHP **mongodb**
1. Per prima cosa bisognare ottenere delle informazioni tramite il comando `phpinfo()`. Quindi è necessario crare un file PHP con il seguente codice ed aprirlo nel Browser 
```
<?php
   
phpinfo();

?>
```
2. Una volta aperto il file ciò che è rilevante sono: **PHP Version**, **Architecture** e **Thread Safefty**

![Immagine](https://www.ilblogdiunprogrammatore.it/image/img/331066420200414326185.png)

3. Scaricare l'estensione mongodb al seguente link https://pecl.php.net/package/mongodb , si aprirà una pagina con le versioni disponibili (**Available Releases**)
in base alla versione di PHP dobbiamo cliccare sulla relativa versione che vogliamo scaricare tramite il link **DLL** 

ESEMPIO: Nel caso di versione di PHP 8.0 cliccare sulla **DLL** 1.13.0 
![Esempio](https://i.postimg.cc/SsWYZmHS/Esempio.png)

4. A questo punto nella nuova pagina che si aprirà in fondo è possibile scaricare la versione di **mongodb** in base 
alla nostra versione di php (scegliendo **Architecture** e **Thread Safefty**)
![Esempio2](https://i.postimg.cc/MKRZVs2w/Esempio2.png)

5. Una volta effettuato il download del file ZIP estraiamolo dove vogliamo e dentro la cartella appena estratta copiamo il file **php_mongodb.dll** e lo andiamo ad incollare
nel percorso: _C:\xampp\php\ext_ 

6. Successivamente torniamo nella cartella precedente a quella in cui abbiamo incollato il file **php_mongodb.dll**, quindi: _C:\xampp\php_ .In questa cartella
dobbiamo cercare ed aprire con un editor di testo (o Visual Studio) il file **php.ini** ed inserire nella lista delle estensioni la seguente riga `extension=php_mongodb.dll`
( !! NON VA INSERITA LA RIGA **extension=mongodb** !! )
![Esempio3](https://i.postimg.cc/85pR2JGK/Esempio3.png)

7.Per capire se l'installazione è andata a buon fine dobbiamo riavviare il server Apache tramite _xampp-control_ e aprire nuovamente il file PHP creato in precedenza 
contenente il comando `phpinfo()` e verificare che nella pagina sia presente la sezione **mongodb** 
![Esempio4](https://i.postimg.cc/TwQ3sh2c/Esempio4.png)

8. A questo punto dovremmo installare **Composer** al seguente link: https://getcomposer.org/ 

9. Per finire, dovremmo aprire il nostro progetto in cui vogliamo utilizzare la nostra libreria **mongodb** mediante Visual Studio, aprire una nuova finestra di Terminale
e copiare e incollare il seguente comando: `composer require mongodb/mongodb`

10. Un esempio di utilizzo di questa libreria è possibile trovarlo all'interno del progetto nella classe _ConnMongoDB.php_ .


#### LINK UTILI 
1) https://www.ilblogdiunprogrammatore.it/53669-php-e-mongodb-impostare-l-ambiente-di-lavoro.html
2) https://github.com/mongodb/mongo-php-library

