//On récupère la liste des utilisateurs générée dans retrieveUsers.php
var userList;
fetch("includes/retrieveUsers.php")
  .then(response => response.json()) // Access and return response's text content
  .then(users => {
    userList = users;
  });

//Erreurs potentielles gérées par ce script
var pswdError = "";

//Vérification de la sécurité du mot de passe
document.getElementById("registerPassword").addEventListener("input", e => {
  const password = e.target.value; // Value of the password field
  let message;
  let messageColor;

  //Mdp sécurisé : taille >= 8 et contient des chiffres
  if (password.length >= 8 && /\d/.test(password)) {
    message = "Sécurité : forte";
    messageColor = "green";
  } else if (password.length >= 6) {
    //Mdp moyen : taille >= 6
    message = "Sécurité : moyenne";
    messageColor = "orange";
  } else {
    //Mdp faible
    message = "Sécurité : faible";
    messageColor = "red";
  }

  const passwordHelpElement = document.getElementById("passwordHelp");
  passwordHelpElement.textContent = message; // helper text
  passwordHelpElement.style.color = messageColor; // helper text color
});

//Verification de la correspondance des 2 champs concernant le mot de passe
document.getElementById("registerPassword").addEventListener("input", e => {
  let password2 = document.getElementById("registerPassword2").value;
  let password1 = e.target.value; // Value of the password 1 field
  samePasswordVerification(password1, password2);
});

document.getElementById("registerPassword2").addEventListener("input", e => {
  let password1 = document.getElementById("registerPassword").value;
  let password2 = e.target.value; // Value of the password 2 field
  samePasswordVerification(password1, password2);
});

//Vérifie si les 2 mots de passe correspondent et agit en conséquence (en affichant les informations correspondantes)
function samePasswordVerification(password1, password2) {
  const passwordIcon = document.getElementById("passwordIcon");
  const validationButton = document.getElementById("registerButton");
  const button = document.getElementById("registerButton");
  let iconName;
  let iconText;

  if (password1 === password2) {
    iconName = "fa fa-check-circle float-right";
    iconText = "Les mots de passe correspondent.";
    pswdError = "";
  } else {
    iconName = "fa fa-exclamation-circle float-right";
    iconText = "Les mots de passe ne correspondent pas.";
    pswdError = iconText;
    button.setAttribute("type", "button");
  }
  passwordIcon.title = iconText;
  passwordIcon.className = iconName;
}

//Verification id et mail lors du clic sur le bouton
document.getElementById("registerButton").addEventListener("click", e => {
  const button = document.getElementById("registerButton");
  let errors = getErrors();
  if (errors !== "") {
    button.setAttribute("type", "button");
    const errorDiv = document.getElementById("registerError");
    const pswdErrorDiv = document.createElement("div");
    const errorStrong = document.createElement("strong");
    errorDiv.innerHTML = "";
    pswdErrorDiv.classList = "alert alert-danger";
    errorStrong.innerHTML = "Erreur ! ";
    pswdErrorDiv.appendChild(errorStrong);
    pswdErrorDiv.innerHTML += errors;
    errorDiv.appendChild(pswdErrorDiv);
  } else {
    button.setAttribute("type", "submit");
  }
});

function checkIfIdExists() {
  const id = document.getElementById("registerId").value;
  return userList.includes(id);
}

function getErrors() {
  let errors = "";
  const emailRegex = /.+@.+\..+/;
  const mailInput = document.getElementById("registerMail");

  if (checkIfIdExists()) {
    errors +=
      "L'utilisateur existe déjà ! Veuillez choisir un autre identifiant. ";
  }

  if (pswdError != "") {
    errors += pswdError;
  }

  if (!emailRegex.test(mailInput.value)) {
    errors += "L'adresse mail fournie est invalide. ";
  }

  return errors;
}
