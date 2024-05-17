export function displayForgot() {
    const forgot = document.querySelector("#form_password");
    const loginForm = document.querySelector("#form_login");
    const register = document.querySelector("#form_register");

  
    if (forgot.style.display === "none" || forgot.style.display === "") {
      forgot.style.display = "block";
      loginForm.style.display = "none";
      register.style.display = "none";
    }
  }

  export function displayLogin() {
    const logIn = document.querySelector("#form_login");
    const parallaxP = document.querySelector(".parallax_text");
    
    // Vérifie si le formulaire de connexion est invisible
    if (logIn.style.display === "none") {
      logIn.style.display = "block";
      parallaxP.style.display = "none"; // Cache le texte parallax lorsque le formulaire apparaît
    } else {
      logIn.style.display = "none"; // Cache le formulaire de connexion si c'est déjà visible
      parallaxP.style.display = "block"; // Affiche le texte parallax quand le formulaire est caché
    }
  }

  export function displayRegister() {
    const register = document.querySelector("#form_register");
    const loginForm = document.querySelector("#form_login");
    const forgot = document.querySelector("#form_password");
  
    if (register.style.display === "none") {
      register.style.display = "block";
      loginForm.style.display = "none"; 
      forgot.style.display = "none"; 
  
    } else {
      register.style.display = "none";
    }
  }