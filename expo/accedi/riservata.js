function showhome(event) {
  document.querySelector("#home").classList.add("active");
  document.querySelector("#resevent").classList.remove("active");
  document.querySelector("form").classList.add("hidden");

  const title = document.querySelector("#title");
  const content = document.querySelector("#content");
  title.innerHTML = "";
  content.innerHTML = "";
  const h1 = document.createElement("h1");
  h1.textContent = "Home";
  title.appendChild(h1);
  fetch("http://localhost/expo/accedi/fetch_prenotati.php").then(onResponseprenotati).then(onJSONprenotati);
  
}
function onResponseprenotati(response) {
  return response.json();
}

function onJSONprenotati(json) {
  const content = document.querySelector("#content");
  var evento = json["evento"];
  var stand = json["stand"];
  
  if (evento.length > 0) {
    const h1 = document.createElement("h1");
    h1.textContent = "Ecco gli eventi prenotati:";
    document.querySelector("#title").appendChild(h1);

    for (i = 0; i < evento.length; i++) {
      const box = document.createElement("div");
      const dati = document.createElement("div");
      const data = document.createElement("div");
      const div = document.createElement("div");
      const gg = document.createElement("div");
      const mm = document.createElement("div");
      
      const end = document.createElement("p");
      const carat = document.createElement("div")
      const cartit = document.createElement("p");
      const dim = document.createElement("p");
      const loc = document.createElement("p");
      var btn = document.createElement("input");
      btn.type = "button";
      btn.value = evento[i].nomeevento + " ED. " + evento[i].edizione;
      btn.className = "nome_evento";
      btn.id = evento[i].id;
      var parts = evento[i].data.split("-");
      var start = new Date(parts[0], parts[1] - 1, parts[2]);
      var id = evento[i].id
      var day = start.getDate();
      var month = ["GEN", "FEB", "MAR", "APR", "MAG", "GIU", "LUG", "AGO", "SET", "OTT", "NOV", "DIC"];
      
      gg.textContent = day.toString();
      mm.textContent = month[start.getMonth()];
      end.textContent = "Data fine: " + evento[i].fine;
        for(p=0 ;p <stand.length;p++){
           if(id == stand[p].evento){
             if(stand[p].modello != null){
               cartit.textContent = "Modello dello stand: " + stand[p].modello;
               dim.textContent = "Metri quadrati dello stand: " + stand[p].dimensione;
               loc.textContent = "Locazione nel padiglione " + stand[p].posizione + " stand numero: "+stand[p].numero;
              
             }else{
              dim.textContent = "Metri quadrati dello stand: " + stand[p].dimensione;
              loc.textContent = "Locazione nella zona esterna numero: " + stand[p].posizione + " stand numero: "+stand[p].numero;
             }
             
           }
        }
       
      

      box.classList.add("box");
      data.classList.add("data");
      
      dati.classList.add("row");
      gg.classList.add("gg");
      mm.classList.add("mm");

      content.appendChild(box);
      box.appendChild(dati);
      box.appendChild(carat);
      dati.appendChild(data);
      dati.appendChild(div);
      data.appendChild(gg);
      data.appendChild(mm);
      div.appendChild(btn);
      div.appendChild(end);
      carat.appendChild(cartit);
      carat.appendChild(dim);
      carat.appendChild(loc);
      btn.onclick = function (event) {
        const a = event.currentTarget;
        a.disabled = true;
        const scelta = a.parentNode.parentNode.parentNode;
        const tit = document.createElement("p");
        const div = document.createElement("div");
        var conferma = document.createElement("input");
        conferma.type = "button";
        conferma.value = "Elimina";
        conferma.className = "elimina";
        conferma.id = a.id;
        tit.textContent="Eliminare la prenotazione?";
        tit.classList.add("domanda");
        div.classList.add("choose");
        conferma.classList.add("conferma");
        scelta.appendChild(div);
        div.appendChild(tit);
        div.appendChild(conferma);
        conferma.onclick =function(event){
          conferma.disabled = true; 
          const formdata = new FormData();
          formdata.append("evento", event.currentTarget.id);
          formdata.append("function", "elimina_evento");
          fetch("fetch.php", { method: "post", body: formdata });
          alert("Eliminazione avvenuta con successo");
          showhome();
        }
      }

    }
  } else {
    const h1 = document.createElement("h1");
    h1.textContent = "Non sei prenotato a nessun evento";
    h1.classList.add("info");
    content.appendChild(h1);
  }
}

function showresevent(event) {
  document.querySelector("#home").classList.remove("active");
  document.querySelector("#resevent").classList.add("active");
  const title = document.querySelector("#title");
  const content = document.querySelector("#content");
  title.innerHTML = "";
  content.innerHTML = "";
  const h1 = document.createElement("h1");
  h1.textContent = "Prenota eventi";
  title.appendChild(h1);
  fetch("./fetch_evento.php").then(onResponseevento).then(onJSONevento);
}

function onResponseevento(response) {
  return response.json();
}

function onJSONevento(json) {


  const h1 = document.createElement("h1");
  h1.textContent = "Selezione un evento per prenotarti";
  document.querySelector("#title").appendChild(h1);
  const content = document.querySelector("#content");
  var evento = json["evento"];
  var modello = json["modello"];
  for (i = 0; i < evento.length; i++) {
    const box = document.createElement("div");
    const dati = document.createElement("div");
    const data = document.createElement("div");
    const div = document.createElement("div");
    const gg = document.createElement("div");
    const mm = document.createElement("div");
    const end = document.createElement("p");
    var costo = document.createElement("p");
    var btn = document.createElement("input");
    btn.type = "button";
    btn.value = evento[i].nomeevento + " ED. " + evento[i].edizione;
    btn.className = "nome_evento";
    btn.id = evento[i].id;
    var parts = evento[i].data.split("-");
    var start = new Date(parts[0], parts[1] - 1, parts[2]);
    const temp = parseInt(evento[i].durata) * parseInt(evento[i].costo);
    costo.textContent = "Costo evento: " + temp + " €";
    var day = start.getDate();
    var month = ["GEN", "FEB", "MAR", "APR", "MAG", "GIU", "LUG", "AGO", "SET", "OTT", "NOV", "DIC"];

    gg.textContent = day.toString();
    mm.textContent = month[start.getMonth()];
    end.textContent = evento[i].fine;
    box.classList.add("box");
    data.classList.add("data");
    dati.classList.add("row");
    gg.classList.add("gg");
    mm.classList.add("mm");
    content.appendChild(box);
    box.appendChild(dati);
    dati.appendChild(data);
    dati.appendChild(div);
    data.appendChild(gg);
    data.appendChild(mm);
    div.appendChild(btn);
    div.appendChild(end);
    div.appendChild(costo);

    btn.onclick = function (event) {
      const a = event.currentTarget;
      a.disabled = true;
      const scelta = a.parentNode.parentNode.parentNode;
      const tit = document.createElement("p");
      const div = document.createElement("div");
      const conferma = document.createElement("div");
      var choose = document.createElement("div");
      var chiuso = document.createElement("input");
      chiuso.type = "button";
      chiuso.value = "Al chiuso";
      chiuso.className = "btn";
      chiuso.id = a.id;
      var aperto = document.createElement("input");
      aperto.type = "button";
      aperto.value = "All'aperto";
      aperto.className = "btn";
      aperto.id = a.id;
      tit.classList.add("domanda");
      div.classList.add("row");
      choose.classList.add("choose");
      conferma.classList.add("conferma");
      tit.textContent = "Preferisci uno stand?";
      scelta.appendChild(choose);
      choose.appendChild(tit);
      choose.appendChild(div);
      choose.appendChild(conferma);
      div.appendChild(aperto);
      div.appendChild(chiuso);

      aperto.onclick = function (event) {
        aperto.className = "check";
        chiuso.className = "btn";
        chiuso.disabled = true;
        aperto.disabled = true;
        const e = event.currentTarget;
        const scelta = e.parentNode.parentNode;
        var conferma = document.createElement("input");
        conferma.type = "button";
        conferma.value = "Conferma Esterno";
        conferma.className = "btn";
        conferma.id = e.id;
        scelta.appendChild(conferma);
        conferma.onclick = function (event) {
          conferma.className = "check";
          conferma.disabled = true;
          const formdata = new FormData();
          formdata.append("evento", event.currentTarget.id);
          formdata.append("function", "insert_esterno");

          fetch("fetch.php", { method: "post", body: formdata });
          alert("Prenotazione avvenuta con sucesso");
          showhome();
        };
      };

      chiuso.onclick = function (event) {
        chiuso.className = "check";
        aperto.className = "btn";
        chiuso.disabled = true;
        aperto.disabled = true;
        const e = event.currentTarget;
        const scelta = e.parentNode.parentNode;
        var conferma = document.createElement("input");
        conferma.type = "submit";
        conferma.value = "Conferma Interno";
        conferma.className = "btn";
        conferma.id = e.id;

        var form = document.createElement("form");
        form.name = "formradio";

        for (o = 0; o < modello.length; o++) {
          var btn = document.createElement("input");
          btn.type = "radio";
          btn.id = modello[o].nomemodello;
          btn.value = modello[o].nomemodello;
          btn.className = "btn";
          btn.name = "modellonome";
          btn.checked = true;
          var label = document.createElement("label");
          label.for = modello[o].nomemodello;
          label.innerHTML = modello[o].nomemodello;
          label.style = ("margin: 5px");

          
          const campo = document.createElement("div");
          campo.classList.add("campo");
          campo.appendChild(btn);
          campo.appendChild(label);
          form.appendChild(campo);    
        }
        scelta.appendChild(form);
        scelta.appendChild(conferma);
        conferma.onclick = function (event) {
          const chek = form.querySelector("input[name=modellonome]:checked");
          conferma.className = "check";
          conferma.disabled = true;
          var valoremodello = chek.value;
          const formdata = new FormData();
          formdata.append("evento", event.currentTarget.id);
          formdata.append("function", "insert_interno");
          formdata.append("modello", valoremodello);
          fetch("fetch.php", { method: "post", body: formdata });
          alert("Prenotazione avvenuta con sucesso");
          showhome();
        };
      };
    };
  }
}

document.querySelector("#home").addEventListener("click", showhome);
document.querySelector("#resevent").addEventListener("click", showresevent);

showhome();
