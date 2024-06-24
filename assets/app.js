import './bootstrap.js';
import './styles/app.scss';

import { Popover } from 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {
  const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
  popoverTriggerList.forEach(popoverTriggerEl => {
    new Popover(popoverTriggerEl);
  });
});


// HAMBURGER 
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector(".hamburger");
    const navLinksHidden = document.querySelector(".nav_links-hidden");
  
  hamburger.addEventListener('click', function() {
    navLinksHidden.classList.toggle('open');
    });
  });
  
  
  
  // PARALLAX
  let parallax_el = document.querySelectorAll(".parallax");
  let timeline = gsap.timeline();
  
  Array.from(parallax_el)
  
  .forEach((el) => {
    timeline.from(
      el,
      {
        top: `${el.offsetHeight / 2 + +el.dataset.distance}px`,
        duration: 3.5,
        ease: "power3.out",
      },
      "1"
    );
  });
  
  timeline.from(
    ".parallax_text",
    {
      y: -750,
      duration: 3.5,
    },
    "2.5"
  );
  
  // EFFET NEIGE
  if (document.querySelector('#home')) {
  const numberOfSnowflakes = 100;
  
  for (let i = 0; i < numberOfSnowflakes; i++) {
    const snowflakes = document.createElement("div");
    snowflakes.classList.add ("home_snowflakes");
    snowflakes.style.left = `${Math.random() * window.innerWidth}px`;
    snowflakes.style.top = `-${Math.random() * window.innerHeight}px`; // Position initiale en haut de la page
    snowflakes.style.width = `${Math.random() * 5 + 3}px`; // Taille aléatoire entre 3px et 8px
    snowflakes.style.height = snowflakes.style.width;
    snowflakes.style.animationDuration = `${Math.random() * 3 + 2}s`;
    snowflakes.style.animationDelay = `${Math.random()}s`;
    snowflakes.style.zIndex = "20";
  
    document.body.appendChild(snowflakes);
  }
}
  
  // DECOMPTE
  let christmas = new Date("2024-12-24T23:59:00"); 
  
  // Fonction pour formater les nombres avec un zéro devant si inférieur à 10
  function caractere(nb) {
      return nb < 10 ? '0' + nb : nb;
  }
  
  function countdown() {
      let today = new Date(); 
      let total_seconds = Math.floor((christmas - today) / 1000); // Calculer le total des secondes restantes
      
      if (total_seconds > 0) {
          let nb_days = Math.floor(total_seconds / (60 * 60 * 24));
          let nb_hours = Math.floor((total_seconds - nb_days * 24 * 60 * 60) / (60 * 60));
          let nb_minutes = Math.floor((total_seconds - nb_days * 24 * 60 * 60 - nb_hours * 60 * 60) / 60);
          let nb_seconds = Math.floor(total_seconds % 60);
  
          document.querySelector("#clock-days").innerHTML = caractere(nb_days);
          document.querySelector("#clock-hours").innerHTML = caractere(nb_hours);
          document.querySelector("#clock-minutes").innerHTML = caractere(nb_minutes);
          document.querySelector("#clock-seconds").innerHTML = caractere(nb_seconds);
      } else {
          // Gérer la fin du compte à rebours si nécessaire
          document.querySelector("#clock-days").innerHTML = '00';
          document.querySelector("#clock-hours").innerHTML = '00';
          document.querySelector("#clock-minutes").innerHTML = '00';
          document.querySelector("#clock-seconds").innerHTML = '00';
      }
      setTimeout(countdown, 1000); // Répéter le compte à rebours toutes les secondes
  }
  
  countdown();
  


