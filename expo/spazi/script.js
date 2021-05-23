//

//

// Create

//

//
function createImg(src) {
  const img = document.createElement("img");
  img.src = src;
  return img;
}
function createTit(tit) {
  const h2 = document.createElement("h2");
  h2.textContent = tit;
  return h2;
}

//

//

// Shoe/hide dett

//

//

function showDett(event) {
  const a = event.currentTarget;
  a.src = "../img/hide.png";
  a.removeEventListener("click", showDett);
  a.addEventListener("click", hideDett);
  const p = event.currentTarget.parentNode.querySelector("p");
  p.classList.remove("hidden");
  p.classList.add("dett");
}

function hideDett(event) {
  const a = event.currentTarget;
  a.src = "../img/show.png";
  a.removeEventListener("click", hideDett);
  a.addEventListener("click", showDett);
  const p = event.currentTarget.parentNode.querySelector("p");
  p.classList.remove("dett");
  p.classList.add("hidden");
}

//

//

// Ricerca

//

//

function search() {
  var input = document.getElementById("barra-ricerca");
  var filtro = input.value.toUpperCase();
  var lista = document.getElementById("elenco");
  var voci = lista.getElementsByTagName("h2");
  var box = lista.getElementsByClassName("box");

  for (var i = 0; i < voci.length; i++) {
    var x = lista.getElementsByTagName("h2")[i];
    var testo = x.textContent || x.innerText;
    if (testo.toUpperCase().indexOf(filtro) > -1) {
      box[i].style.display = "";
    } else {
      box[i].style.display = "none";
    }
  }
}
const r = document.querySelector("#barra-ricerca");
r.addEventListener("keyup", search);

//

//

//

//

//

//

function addFavorite(event) {
  const grid = document.querySelector("#elenco");
  const preferiti = document.querySelector("#preferiti");
  preferiti.classList.remove("hidden");
  const e = event.currentTarget;
  e.removeEventListener("click", addFavorite);
  e.src = "../img/checked.png";
  const i = event.currentTarget.dataset.id;
  const selection = grid.childNodes;
  const tit = selection[i].querySelector(".title h2");
  const h2 = createTit(tit.textContent);
  var src = "../img/checked.png";
  const checked = createImg(src);
  var src = selection[i].querySelector(".img");
  const img = createImg(src.src);
  //creo il box
  const box = document.createElement("div");
  const title = document.createElement("div");
  //aggiungo gli attributi css
  box.classList.add("box");
  title.classList.add("title");
  checked.classList.add("favorite");
  checked.dataset.id = i;
  checked.addEventListener("click", removeFavorite);
  img.classList.add("img");

  //inserisco nel box tutti gli elementi
  grid.appendChild(box);
  box.appendChild(title);
  title.appendChild(h2);
  title.appendChild(checked);
  box.appendChild(img);

  const section = preferiti.querySelector("div");
  section.appendChild(box);
}

function removeFavorite(event) {
  //Assegnazione evento al box fuori dai preferiti
  const n = event.currentTarget.dataset.id;
  const elenco = document.querySelectorAll("#elenco div.box");

  for (let i = 0; i < elenco.length; i++) {
    if (i == n) {
      const favorite = elenco[n].getElementsByClassName("favorite");
      //grazie all'if favorite avara' un solo valore
      favorite[0].addEventListener("click", addFavorite);
      favorite[0].src = "../img/unchecked.png";
    }
  }

  //tolgi box cliccato
  const a = event.currentTarget;
  const box = a.parentNode.parentNode;
  box.remove("div");

  // contatore elementi preferiti
  const div = document.querySelector(".stand-grid");
  var c = div.childNodes.length;

  if (c == 0) {
    const preferiti = document.querySelector("#preferiti");
    preferiti.classList.add("hidden");
  }
}

//

//

// Stampa dinamica Stand al chiuso

//

//

function caricaeventi() {
  fetch("./caricastand.php").then(onResponse).then(onJSON);
}
function onResponse(response) {
  return response.json();
}

function onJSON(json) {
  var stands =json;

  if(stands.length > 0){
   const grid = document.querySelector("#elenco");
   for (let i = 0; i < stands.length; i++) {
     var stand =stands[i];
     const tit = stand.title;
     const h2 = createTit(tit);
     var src = "../img/unchecked.png";
     const checked = createImg(src);
     var src = stand.img;
     const img = createImg(src);
     var src = "../img/show.png";
     const arrow = createImg(src);
     const p = document.createElement("p");
     p.textContent = stand.dett;
     
     //creo il box
     const box = document.createElement("div");
     const title = document.createElement("div");
     const div = document.createElement("div");
     //aggiungo gli attributi css
     box.classList.add("box");
     box.id =i;
     title.classList.add("title");
     div.classList.add("row");
     checked.classList.add("favorite");
     checked.addEventListener("click", addFavorite);
     checked.dataset.id = i;
     img.classList.add("img");
     p.classList.add("hidden");
     arrow.classList.add("arrow");
     arrow.addEventListener("click", showDett);
     //inserisco nel box tutti gli elementi
     grid.appendChild(box);
     box.appendChild(title);
     box.appendChild(div);
     title.appendChild(h2);
     title.appendChild(checked);
     div.appendChild(img);
     div.appendChild(p);
     box.appendChild(arrow);
    }
  }else {
    console.log("Errore caricamento stand");
  }
}
caricaeventi();