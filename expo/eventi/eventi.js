
function createImg(src) {
  const img = document.createElement("img");
  img.src = src;
  return img;
}


function caricaeventi() {
  fetch("./caricaeventi.php").then(onResponse).then(onJSON);
}
function onResponse(response) {
  return response.json();
}

function onJSON(json) {
  
      var events = json;
      if (events.length > 0) {
        var calendar = document.getElementById("calendar");
        for (i = 0; i < events.length; i++) {
          var event = events[i];

          //seleziono e organizzo le date
          var parts = event.data.split("-");
          var start = new Date(parts[0], parts[1] - 1, parts[2]);

          var day = start.getDate();
          var month = ["GEN", "FEB", "MAR", "APR", "MAG", "GIU", "LUG", "AGO", "SET", "OTT", "NOV", "DIC"];

          //creo tutti gli elementi
          const box = document.createElement("div");
          const data = document.createElement("div");
          const gg = document.createElement("div");
          const mm = document.createElement("div");
          const tit = document.createElement("h2");
          const end = document.createElement("p");
          

          const src = event.img;

          const img = createImg(src);

          tit.textContent = event.nomeevento +" Edizione N° " + event.edizione;
          end.textContent = "Fine evento: " + event.fine;
          
          gg.textContent = day.toString();
          mm.textContent = month[start.getMonth()];
          box.classList.add("box");
          data.classList.add("data");
          gg.classList.add("gg");
          mm.classList.add("mm");
          img.classList.add("img");

          calendar.appendChild(box);
          box.appendChild(data);
          data.appendChild(gg);
          data.appendChild(mm);
          box.appendChild(img);
          box.appendChild(tit);
          box.appendChild(end);
          removeLoading();
        }
      } else {
        console.log("Nessun evento trovato.");
      }
    
}

function removeLoading() {
  const loading = document.getElementById("loading");

  loading.classList.add("hidden");
}

caricaeventi();