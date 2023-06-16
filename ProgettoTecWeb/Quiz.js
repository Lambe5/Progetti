//var risposta0, risposta1, risposta2, risposta3;
var arrDomande = ["What is the name of this animal?", "What is the lifespan of this animal?", "What is the maximum length of this animal in m?", "What is the maximum weight of this animal in Kg?", 
"What is the habitat of this animal?", "What are/is the geographic location of this animal?", "What is the diet of this animal?"];
var arrRisposte = [];
var arrRisposteIni = [];
var i = 0;
var id = [];
var tentataRisp = false;
var score = 0;


animalRequest(0);
document.getElementById('finishGame').style.display = 'none';
async function next(){
    animalRequest(i);
    tentataRisp = false;
}

async function animalRequest(i) {
        let request = new XMLHttpRequest();
        request.open("GET", "https://zoo-animal-api.herokuapp.com/animals/rand/", true);
        request.send();
        request.onload = () => {
            if (request.status == 200) {
                var animalJSON = JSON.parse(request.response)
                // console.log(animalJSON);
                animalPresent(animalJSON, i);
                // console.log("indietro1");
            } else {
            console.log(`error ${request.status} ${request.statusText}`)
            }
        }
}

var externalIndex;

async function animalPresent(animalJSON, i) {
    // console.log("i in present" + i);
    var diverso = true;
    if(i == 0){
        document.getElementsByTagName("img")[0].src= animalJSON.image_link;
        var lunghezza = arrDomande.length - 1;
        var index = Math.floor(Math.random() * lunghezza);
        document.getElementById('domandaCorrente').textContent = arrDomande[index];
        externalIndex = index;
    }
    switch(externalIndex){
        case 0:
            arrRisposteIni[i] = animalJSON.name;
            break;
        case 1:
            arrRisposteIni[i] = animalJSON.lifespan;
            break;
        case 2:
            arrRisposteIni[i] = parseFloat(animalJSON.length_max) * 0.3;
            arrRisposteIni[i] = arrotonda(arrRisposteIni[i]);
            break;
        case 3:
            arrRisposteIni[i] = parseFloat(animalJSON.weight_max) * 0.45;
            arrRisposteIni[i] = arrotonda(arrRisposteIni[i]);
            break;
        case 4:
            arrRisposteIni[i] = animalJSON.habitat;
            break;
        case 5:
            arrRisposteIni[i] = animalJSON.geo_range;
            break;
        case 6:
            arrRisposteIni[i] = animalJSON.diet;
            break;  
    }

    //faccio il controllo che sia diverso dalle altre risposte
    for(var j = 0; j < arrRisposte.length; j++){
        if(arrRisposte[j] == arrRisposteIni[i]){
            diverso = false;
            break;
        }
    }
    if(diverso){
        arrRisposte[i] = arrRisposteIni[i];
    }

    if(i == 3){
        assegnaRisposta();
    } 
    
    if(diverso)
        i++;

    if(i<=3)
        animalRequest(i);
}

async function assegnaRisposta(){
    var arr = [];
    var index = 0;
    
    //mi salvo le posizioni in un array
    while(index < 4){
        var random = Math.floor(Math.random() * 4);
        if(!arr.includes(random)){
            arr[index] = random;
            index++;
        }
    }

    //qui metto all'elemto di valore 0 l'id primo così che sarà sempre asseganto allo 0 indipendentemente dalla sual posizione nell'array
    for(var i = 0; i < arr.length; i++){
        switch(arr[i]){
            case 0:
                id[i] = "primo";
                break;
            case 1:
                id[i] = "secondo";  
                break;
            case 2:
                id[i] = "terzo";
                break;
            case 3:
                id[i] = "quarto";
                break;
        }
    }

    document.getElementById('primo').style.backgroundColor = '';
    document.getElementById('secondo').style.backgroundColor = '';
    document.getElementById('terzo').style.backgroundColor = '';
    document.getElementById('quarto').style.backgroundColor = '';
    document.getElementById(id[0]).textContent = arrRisposteIni[0];
    document.getElementById(id[1]).textContent = arrRisposteIni[1];
    document.getElementById(id[2]).textContent = arrRisposteIni[2];
    document.getElementById(id[3]).textContent = arrRisposteIni[3];
}

function rispostaSelezionata(idCorrente){
    if(!tentataRisp){
        if(document.getElementById(idCorrente).textContent == arrRisposteIni[0]){
            document.getElementById(idCorrente).style.backgroundColor = 'rgb(1, 195, 1)';
            score++;
            document.getElementById('numeroMosse').textContent = score;
            setTimeout(() => {
               next();
              }, 1000);
        }
        else{
            document.getElementById(idCorrente).style.backgroundColor = 'red';
            document.getElementById(id[0]).style.backgroundColor = 'rgb(1, 195, 1)';
            document.getElementById('finishGame').style.display = 'flex';
            document.getElementById('gameOver').style.display = 'block';
            document.getElementById('NumMosse').value = score;
            document.getElementById('NumMosse').style.display = 'none';
        } 
    }
    tentataRisp = true;
}

function arrotonda(w){
    var weight = Number((Math.abs(w) * 100).toPrecision(15));
    var v = Math.round(weight) / 100 * Math.sign(w);
      if(v == 0)
        return 0.001;
      else return v;
  }

var v = 0.000087;
v = arrotonda(v);
console.log("ECCO QUI: " + v);