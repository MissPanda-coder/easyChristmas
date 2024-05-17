const form = document.querySelector("#form_login");
const emailEl = document.forms.form_login.email_login;
const passwordEl = document.forms.form_login.password_login;

function isRequired(elementValue) {
  if (elementValue == "") {
    return false;
  } else {
    return true;
  }
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

export function checkEmailLogin() {
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

export function checkPassLogin() {
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