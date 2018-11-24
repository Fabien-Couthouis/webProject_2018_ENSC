document.getElementById("addCampaignAdmins").addEventListener("click", e => {
  const name = document.getElementById("adminName");
  const list = document.getElementById("adminList");
  const br = document.createElement("br");
  const span = document.createElement("span");

  if (name.value != "") {
    span.textContent = name.value;
    list.appendChild(span);
    list.appendChild(br);
    name.value = "";
  }
});
