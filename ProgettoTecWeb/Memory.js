var max = 0;

function showCards() {
  document.getElementById("memory").style.display = "flex";
  document.getElementById("title").style.display = "none";
  /*var select = document.getElementById("choose");
  var value = select.options[select.selectedIndex].value;
  switch (value) {
    case "Easy":
      max = 6;
      document.getElementsByName("my1")[0].style.display = "none";
      document.getElementsByName("my1")[1].style.display = "none";
      document.getElementsByName("my2")[0].style.display = "none";
      document.getElementsByName("my2")[1].style.display = "none";
      document.getElementsByName("my3")[0].style.display = "none";
      document.getElementsByName("my3")[1].style.display = "none";
      document.getElementsByName("my4")[0].style.display = "none";
      document.getElementsByName("my4")[1].style.display = "none";
      document.getElementById("memory").style.marginTop = "10%";
      break;
    case "Medium":
      max = 8;
      document.getElementsByName("my1")[0].style.display = "none";
      document.getElementsByName("my1")[1].style.display = "none";
      document.getElementsByName("my2")[0].style.display = "none";
      document.getElementsByName("my2")[1].style.display = "none";
      document.getElementById("memory").style.marginTop = "5%";
      break;
    case "Difficult":*/
      max = 8;
  //}
}
const cards = document.querySelectorAll(".memory-card");
document.getElementById("reload").style.display = "none";
document.getElementById("exit").style.display = "none";
let hasFlippedCard = false;
let lockBoard = false;
let firstCard, secondCard;
var count = 0;
var movesCounter = 0;

function flipCard() {
  //alert("dentro2");
  if (lockBoard) return;
  if (this === firstCard) return;

  this.classList.add("flip");

  if (!hasFlippedCard) {
    // first click
    hasFlippedCard = true;
    firstCard = this;

    return;
  }

  // second click
  secondCard = this;

  checkForMatch();
}

function checkForMatch() {
  //alert("dentro3");
  let isMatch = firstCard.dataset.framework === secondCard.dataset.framework;
  movesCounter++;
  isMatch ? disableCards() : unflipCards();
}

function disableCards() {
  //alert("dentro4");
  setTimeout(() => {
    setHidden(firstCard.dataset.framework);
    firstCard.removeEventListener("click", flipCard);
    secondCard.removeEventListener("click", flipCard);
    resetBoard();
  }, 1000);

  setTimeout(() => {
    //firstCard.remove();
    //secondCard.remove();

    count++;
    if (count >= max) {
      console.log("complimenti");
      document.getElementById("reload").style.display = "block";
      document.getElementById("exit").style.display = "block";
      document.getElementById("movesNumber").innerHTML += "Moves number: " + movesCounter;
      document.getElementById('NumMosse').value = movesCounter;
      // document.getElementById("endGame").style.marginTop = "20%";
    }
  }, 1800); //ho messo 1600 anzichè 1500 così da evitare un bug
}

function unflipCards() {
  //alert("dentro5");
  lockBoard = true;

  setTimeout(() => {
    firstCard.classList.remove("flip");
    secondCard.classList.remove("flip");

    resetBoard();
  }, 1500);
}

function resetBoard() {
  //alert("dentro6");
  [hasFlippedCard, lockBoard] = [false, false];
  [firstCard, secondCard] = [null, null];
}

(function shuffle() {
  //alert("dentro1");
  cards.forEach((card) => {
    let randomPos = Math.floor(Math.random() * 12);
    card.style.order = randomPos;
  });
})();

cards.forEach((card) => card.addEventListener("click", flipCard));

function setHidden(name) {
  switch (name) {
    case "aurelia":
      document.getElementById("card1").style.visibility = "hidden";
      document.getElementById("card2").style.visibility = "hidden";
      break;
    case "vue":
      document.getElementById("card3").style.visibility = "hidden";
      document.getElementById("card4").style.visibility = "hidden";
      break;
    case "angular":
      document.getElementById("card5").style.visibility = "hidden";
      document.getElementById("card6").style.visibility = "hidden";
      break;
    case "ember":
      document.getElementById("card7").style.visibility = "hidden";
      document.getElementById("card8").style.visibility = "hidden";
      break;
    case "backbone":
      document.getElementById("card9").style.visibility = "hidden";
      document.getElementById("card10").style.visibility = "hidden";
      break;
    case "react":
      document.getElementById("card11").style.visibility = "hidden";
      document.getElementById("card12").style.visibility = "hidden";
      break;
    case "my1":
      document.getElementById("card13").style.visibility = "hidden";
      document.getElementById("card14").style.visibility = "hidden";
      break;
    case "my2":
      document.getElementById("card15").style.visibility = "hidden";
      document.getElementById("card16").style.visibility = "hidden";
      break;
    case "my3":
      document.getElementById("card17").style.visibility = "hidden";
      document.getElementById("card18").style.visibility = "hidden";
      break;
    case "my4":
      document.getElementById("card19").style.visibility = "hidden";
      document.getElementById("card20").style.visibility = "hidden";
      break;
  }
}
