/*function maggiore(){
    document.getElementById('im1').style.display = 'none';
    setTimeout(() => {
        document.getElementById('im1').style.display = 'block';
      }, 700); }

function minore(){
    document.getElementById('immagini').style.flexDirection = 'row';
}*/


function confronta(){
    if(parseFloat(weight1) < parseFloat(weight2)){
      return 1;
    }
    else if(parseFloat(weight1) > parseFloat(weight2)){
      return 2;
    }
    else return 0;
  }
  
  
  function scambiaImgs(){
    document.getElementById("im1").src= document.getElementById("im2").src;
    document.getElementsByName('nomeAnimale')[0].innerHTML = name2;
    document.getElementsByName('peso')[0].innerHTML = weight2 + " Kg";
    animalRequest2();    
  }
  
  
  function max(){
    var confronto = confronta();
    if(confronto == 1 || confronto == 0){
      mosse++;
      document.getElementById('numeroMosse').textContent = mosse;
      scambiaImgs();
    }
    else {
      document.getElementById('numeroMosse').textContent = mosse;
      endGame();
    }
  }
  
  function min(){
    var confronto = confronta();
    if(confronto == 2 || confronto == 0){
      mosse++;
      document.getElementById('numeroMosse').textContent = mosse;
      scambiaImgs();
    }
    else {
      document.getElementById('numeroMosse').textContent = mosse;
      endGame();
   }
  }
  
  function endGame(){
    document.getElementsByName('peso')[1].innerHTML = weight2 + " Kg";
    document.getElementsByName('peso')[1].style.color = 'red';
    var bottoni = document.getElementsByClassName('bottoni');
    var input = document.getElementsByTagName('input');
    for(var i = 0; i < bottoni.length; i++){
        bottoni[i].style.display = 'none';
    }
    for(var i = 0; i < input.length; i++){
      if(input[i].id != "NumMosse")
      input[i].style.display = 'inline-block';
    }
    document.getElementById('gameOver').style.display = 'block';
    document.getElementById('NumMosse').value = mosse;
  }
  
  
  var weight1, weight2;
  var name1, name2;
  var mosse = 0;
  var canAssign = false;
  animalRequest2();
  animalRequest();
  
  function animalRequest() {
    let request = new XMLHttpRequest();
    request.open("GET", "https://zoo-animal-api.herokuapp.com/animals/rand/");
    request.send();
    request.onload = () => {
      //console.log(request);
      if (request.status == 200) {
        var animalJSON = JSON.parse(request.response)
        //console.log(animalJSON);
  
        animalPresent(animalJSON);
      } else {
        console.log(`error ${request.status} ${request.statusText}`);
      }
    };
  }
  
  function animalRequest2() {
    let request = new XMLHttpRequest();
    request.open("GET", "https://zoo-animal-api.herokuapp.com/animals/rand/");
    request.send();
    request.onload = () => {
      //console.log(request);
      if (request.status == 200) {
        var animalJSON = JSON.parse(request.response)
        //console.log(animalJSON);
  
        animalPresent2(animalJSON);
      } else {
        console.log(`error ${request.status} ${request.statusText}`);
      }
    };
  }
  
  function animalPresent2(animalJSON) {
      name2 = animalJSON.name;
      if(canAssign)
        weight1 = weight2;
      //weight2 = animalJSON.weight_max;
      weight2 = parseFloat(animalJSON.weight_max) * 0.45;
      weight2 = arrotonda(weight2);
      console.log("dentro animalPresent2, peso1: " + weight1);
      console.log("dentro animalPresent2, peso2: " + weight2);
      document.getElementById("im2").src= animalJSON.image_link;
      document.getElementsByName('nomeAnimale')[1].innerHTML = name2;
      canAssign = true;
    }
  
  
  function animalPresent(animalJSON) {
    name1 = animalJSON.name;
    weight1 = parseFloat(animalJSON.weight_max) * 0.45;
    weight1 = arrotonda(weight1);
    console.log("dentro parsent, peso1: " + weight1);
    document.getElementById("im1").src= animalJSON.image_link;
    document.getElementsByName('nomeAnimale')[0].innerHTML = name1;
    document.getElementsByName('peso')[0].innerHTML = weight1 + " Kg";
  }
  
  function arrotonda(w){
    var weight = Number((Math.abs(w) * 100).toPrecision(15));
    var weight = Number((Math.abs(w) * 100).toPrecision(15));
    var v = Math.round(weight) / 100 * Math.sign(w);
      if(v == 0)
        return 0.001;
      else return v;    
  }
  
  