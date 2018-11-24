//On récupère la liste des utilisateurs générée dans retrieveUsers.php et on met tout ça dans la datalist de la page
let userList;
fetch("includes/retrieveUsers.php")
  .then(response => response.json()) // Access and return response's text content
  .then(users => {
    userList = users;
    //On supprime l'utilisateur de la liste des propositions d'utilisateurs
    userList.splice(userList.indexOf(userId), 1);
    const adminSuggestions = document.getElementById("admins");
    for (let i = 0; i < userList.length; i++) {
      const option = document.createElement("option");
      option.value = userList[i];
      adminSuggestions.appendChild(option);
    }
  });

document.getElementById("addExperience").addEventListener("click", e => {
  const area = document.getElementById("expArea");
  addExpInput(area);
});

//Ajoute le nouvel admin sous forme de texte
function addAdmin(value) {
  const adminInput = document.getElementById("campaignAdmins");
  const addedAdmins = document.getElementById("addedAdmins");
  const span = document.createElement("span");
  const br = document.createElement("br");
  span.innerHTML +=
    '<i class="fa fa-minus-circle" onClick=removeAdmin(this.parentNode)>' +
    " " +
    value +
    "</i>";
  span.className = "adminNames";

  const input = document.createElement("input");
  input.type = "text";
  input.hidden = true;
  input.name = "adminNames[]";
  console.log(input.name);
  input.value = value;
  span.appendChild(input);
  span.appendChild(br);
  addedAdmins.appendChild(span);

  adminInput.value = "";
  if (document.contains(document.getElementById("suggestionList")))
    document.getElementById("suggestionList").remove();
}

//s'execute lorsque l'utilisateur click sur un nom d'expérimentateur à ajouter
function onAdminClick() {
  let val = document.getElementById("campaignAdmins").value;
  let opts = document.getElementById("admins").childNodes;
  for (var i = 0; i < opts.length; i++) {
    if (opts[i].value === val) {
      addAdmin(val);
      break;
    }
  }
}

function removeAdmin(node) {
  node.remove();
}

function addExpInput(area) {
  const tr = document.createElement("tr");

  const td1 = document.createElement("td");
  td1.innerHTML =
    '<input type="text" class="form-control" name="expNames[]" placeholder="Entrez le nom de votre expérience">';
  tr.appendChild(td1);

  const td2 = document.createElement("td");
  td2.innerHTML =
    '<select name="expTypes[]" class="custom-select"> <option value="SUS" selected>SUS</option><option value="NASA-TLX">NASA-TLX</option></select>';
  tr.appendChild(td2);

  const td3 = document.createElement("td");
  td3.innerHTML =
    '<textarea class="form-control" name="expDescs[]" placeholder="Description de l\'expérience" rows="2"></textarea>';
  tr.appendChild(td3);

  area.appendChild(tr);

  //Scroll en bas du document pour eviter à l'utilisateur de la faire manuellement (ergonomie)
  document.body.scrollTop = document.body.scrollHeight;
}
