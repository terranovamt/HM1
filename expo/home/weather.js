// API KEY
const key = "6d2d654b77dccaa386ef51d41bc068dd";

const weather = {};
weather.temperature = {
  unit: "celsius",
};

// API
let latitude = 37.03171;
let longitude = 15.06388;
let api = `http://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&lang=it&appid=${key}`;
fetch(api)
  .then(function (response) {
    let data = response.json();
    return data;
  })

  .then(function (data) {
    const KELVIN = 273;
    weather.temperature.value = Math.floor(data.main.temp - KELVIN);
    weather.description = data.weather[0].description;
    weather.iconId = data.weather[0].icon;
    weather.city = data.name;
    weather.country = data.sys.country;
  })
  .then(function () {
    displayWeather();
  });
// Selezione elementi
const iconElement = document.querySelector(".weather-icon");
const tempElement = document.querySelector(".temperature-value p");
const descElement = document.querySelector(".temperature-description p");
const locationElement = document.querySelector(".location p");
const notificationElement = document.querySelector(".notification");
function displayWeather() {
  iconElement.innerHTML = `<img src="./icons/${weather.iconId}.png"/>`;
  tempElement.innerHTML = `${weather.temperature.value}°<span>C</span>`;
  descElement.innerHTML = weather.description;
  locationElement.innerHTML = `${weather.city}, ${weather.country}`;
}
