const form = document.querySelector("#form_register");
const togglePassword = document.querySelector(".toggle_password");
const nameEl = document.forms.form_register.name;
const emailEl = document.forms.form_register.email;
const passwordEl = document.forms.form_register.password;
const confPassEl = document.forms.form_register["password-confirm"];
const cguEl = document.forms.form_register.cgu;

document.querySelectorAll(".toggle-password").forEach(function (element) {
  element.addEventListener("click", function () {
    this.classList.toggle("fa-eye-slash");
    this.classList.toggle("fa-eye");
    const input = document.querySelector("#password");
    if (input.getAttribute("type") === "password") {
      input.setAttribute("type", "text");
    } else {
      input.setAttribute("type", "password");
    }
  });
});

function isRequired(elementValue) {
  if (elementValue == "") {
    return false;
  } else {
    return true;
  }
}

function isBetween(length, min, max) {
  if (length < min || length > max) {
    return false;
  } else {
    return true;
  }
}

function isNameValid(name) {
  const re = /^(?!.*\broot\b)[a-zA-Z]+$/;
  return re.test(name);
}

function isFirstNameValid(firstName) {
  const re = /^(?!.*\broot\b)[a-zA-Z]+$/;
  return re.test(firstName);
}


function isValidEmail(email) {
  const regex =
    /^(?!.*@yopmail\.com$)[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  return regex.test(email);
}

function isPasswordValid(password) {
  const re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})/;
  return re.test(password);
}

function isCguValid(cguCheckbox) {
  return cguCheckbox.checked;
}

function showError(input, message) {
  const formField = input.parentElement;
  formField.classList.remove("success");
  formField.classList.add("error");
  const errorEl = formField.querySelector("small");
  errorEl.textContent = message;
}

function showSuccess(input) {
  const formField = input.parentElement;
  formField.classList.remove("error");
  formField.classList.add("success");
  const errorEl = formField.querySelector("small");
  errorEl.textContent = "";
}

export function checkName() {
  let valid = false;
  const min = 2,
    max = 25;
  const name = nameEl.value.trim();
  if (!isRequired(name)) {
    showError(nameEl, "Le champ ne peut pas être vide");
  } else if (!isBetween(name.length, min, max)) {
    showError(nameEl, `Le nom doit avoir entre ${min} et ${max} caractères.`);
  } else if (!isNameValid(name)) {
    showError(
      nameEl,
      `Le nom d'utilisateur ne doit contenir que des lettres et ne peut être "root".`
    );
  } else {
    showSuccess(nameEl);
    valid = true;
  }
  return valid;
}
export function checkFirstName() {
  let valid = false;
  const min = 2,
    max = 25;
  const firstName = firstNameEl.value.trim();
  if (!isRequired(firstName)) {
    showError(firstNameEl, "Le prénom ne peut pas être vide");
  } else if (!isBetween(firstName.length, min, max)) {
    showError(
      firstNameEl,
      `Le prénom doit avoir entre ${min} et ${max} caractères.`
    );
  } else if (!isFirstNameValid(firstName)) {
    showError(
      firstNameEl,
      `Le prénom ne doit contenir que des lettres et ne peut être "root".`
    );
  } else {
    showSuccess(firstNameEl);
    valid = true;
  }
  return valid;
}


export function checkPhone() {
  const phone = phoneEl.value.trim();
  if (!isRequired(phone)) {
    showError(phoneEl, "Le champ ne peut pas être vide");
    return false;
  } else if (!isPhoneValid(phone)) {
    showError(phoneEl, "Le numéro de téléphone n'est pas valide");
    return false;
  } else {
    showSuccess(phoneEl);
    return true;
  }
}

export function checkEmail() {
  let valid = false;
  const email = emailEl.value.trim();
  if (!isRequired(email)) {
    showError(emailEl, `L'email ne peut être vide.`);
  } else if (!isValidEmail(email)) {
    showError(
      emailEl,
      `L'email doit respecter le format email et ne peut un yopmail.com.`
    );
  } else {
    showSuccess(email);
    valid = true;
  }
  return valid;
}

export function checkDob() {
  let valid = false;
  const dobval = dobEl.value;
  const dob = new Date(dobval);
  const today = new Date();
  const twentyOneBirthday = new Date(
    dob.getFullYear() + 18,
    dob.getMonth(),
    dob.getDate()
  );
  if (!isRequired(dobval)) {
    showError(dobEl, "Vous devez renseigner votre âge");
  } else if (twentyOneBirthday > today) {
    showError(dobEl, "Vous devez avoir 18 ans pour vous inscrire");
  } else {
    showSuccess(dobEl);
    valid = true;
  }
  return valid;
}
export function checkPass() {
  let valid = false;
  const pass = passwordEl.value.trim();
  if (!isRequired(pass)) {
    showError(passwordEl, "Le mot de passe ne peut être vide");
  } else if (!isPasswordValid(pass)) {
    showError(
      passwordEl,
      "Le mot de passe doit comprendre au moins une majuscule un chiffre et un caratére spécial situé dans cette liste : (!@#$%^&*)"
    );
  } else {
    showSuccess(passwordEl);
    valid = true;
  }
  return valid;
}
export function confPass() {
  let valid = false;
  const pass = passwordEl.value.trim();
  const conf = confPassEl.value.trim();
  if (!isRequired(conf)) {
    showError(confPassEl, "La confirmation du mot de passe ne peut être vide");
  } else if (pass !== conf) {
    showError(confPassEl, "Les mots de passes ne correspondent pas");
  } else {
    showSuccess(confPassEl);
    valid = true;
  }
  return valid;
}
export function checkCgu() {
  const cgvCheckbox = cgvEl;
  if (!isCguValid(cgvCheckbox)) {
    showError(
      cgvEl,
      "Vous devez confirmer que vous avez lu les CGV et que vous les acceptez"
    );
    return false;
  } else {
    showSuccess(cgvEl);
    return true;
  }
}