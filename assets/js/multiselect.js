var expanded = false;

function showCheckboxes_publico() {
  var checkboxes_publico = document.getElementById("checkboxes-publico");
  if (!expanded) {
    checkboxes_publico.style.display = "block";
    expanded = true;
  } else {
    checkboxes_publico.style.display = "none";
    expanded = false;
  }
}

function showCheckboxes_conteudo() {
  var checkboxes_conteudo= document.getElementById("checkboxes-conteudo");
  if (!expanded) {
    checkboxes_conteudo.style.display = "block";
    expanded = true;
  } else {
    checkboxes_conteudo.style.display = "none";
    expanded = false;
  }
}

function showCheckboxes_ferramenta() {
  var checkboxes_ferramenta= document.getElementById("checkboxes-ferramenta");
  if (!expanded) {
    checkboxes_ferramenta.style.display = "block";
    expanded = true;
  } else {
    checkboxes_ferramenta.style.display = "none";
    expanded = false;
  }
}

function showCheckboxes_habilidade() {
  var checkboxes_habilidade= document.getElementById("checkboxes-habilidade");
  if (!expanded) {
    checkboxes_habilidade.style.display = "block";
    expanded = true;
  } else {
    checkboxes_habilidade.style.display = "none";
    expanded = false;
  }
}

