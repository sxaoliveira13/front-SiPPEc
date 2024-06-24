window.addEventListener('DOMContentLoaded', () => {
    document.getElementById("userName").textContent = userData.userName;
    handleButtonsAndInputs();

});

function handleButtonsAndInputs() {
    document.getElementById('btnDeleteCatalog').setAttribute("disabled", true);

    document.getElementById('btnNewArticle')?.addEventListener('click', (e) => {
        insertCatalog("articleForm", 1, e.target);
    });

    document.getElementById('btnNewGame')?.addEventListener('click', (e) => {
        insertCatalog("gameForm", 2, e.target);
    });

    document.getElementById('btnNewMethod')?.addEventListener('click', (e) => {
        insertCatalog("methodForm", 3, e.target);
    });

    document.getElementById('btnLogout')?.addEventListener('click', (e) => {
        logout();
    });

    document.getElementById('catalogDeleteInput')?.addEventListener('keyup', (e) => {
        if (e.target.value === "excluir") {
            document.getElementById('btnDeleteCatalog').removeAttribute("disabled");
        }
        else {
            document.getElementById('btnDeleteCatalog').setAttribute("disabled", true);
        }
    });

    document.getElementById('btnDeleteCatalog')?.addEventListener('click', (e) => {
        deleteCatalog();
    });
}



var catalogs = {};
fetchUserCatalogs();
async function fetchUserCatalogs() {
    document.getElementById("catalogListLoader")?.classList.add("d-none");

    fetch(`${apiUrl}/catalog/get.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 'userId': userData.userId })
    }).then((resp) => resp.json())
        .then(async (resp) => {
            await locks['onload'];

            if (!resp['success']) {
                alert(resp['msg']);
                return;
            }

            setTimeout(() => {
                document.getElementById("catalogsQuantity").textContent = resp['data'].length;
                document.getElementById("catalogListLoader").classList.add("d-none");
                buildCatalogsList(resp['data']);
            }, 700);
        }).catch((err) => {
            alert('Erro desconhecido!');
        });
}

function buildCatalogsList(data) {
    document.getElementById("catalogsList").innerHTML = "";

    if (data.length === 0) {
        document.getElementById("catalogsList").innerHTML = "<h3 style='transform: translateY(-2.5rem)' class='u-text-muted--2 d-flex align-items-center text-center h-100 mb-0'>Você não possui catálogos cadastrados</h3>"
    }

    data.forEach((catalog) => {
        const [title, catalogId, createdAt] = [catalog.title, catalog.catalogId, catalog.createdAt];

        let html = `
            <li id="catalog_${catalogId}" class="main__section-catalog">
                <div class="catalog__header">
                    <h3 id="catalogTitle-${catalogId}" class="catalog__header-title mb-0">${title}</h3>
                    <button class="catalog__header-button" onclick="buildEditModalFields(${catalogId})" data-bs-toggle="modal" data-bs-target="#editCatalogModal">Ver</button>
                </div>
                <div class="catalog__body">
                    <p class="my-3">Solicitação de cadastro <b class="u-text-color u-text-color--green">aprovada</b></p>
                </div>
                <div class="catalog__footer">
                    <p class="u-text-color u-text-color--muted mb-0">${beautifyDate(createdAt)}</p>
                </div>
            </li> 
        `;

        catalogs[catalogId] = catalog;

        document.getElementById("catalogsList").insertAdjacentHTML("beforeend", html);
    });
}
function buildEditModalFields(catalogId) {
    const currentCatalog = catalogs[catalogId];

    document.getElementById("catalogId").value = currentCatalog.catalogId;
    document.getElementById("categoryId").value = currentCatalog.categoryId;
    document.getElementById("catalogTitle").value = currentCatalog.title;
    document.getElementById("catalogContent").value = currentCatalog.content;
    document.getElementById("catalogTool").value = currentCatalog.toolId;
    document.getElementById("catalogPublic").value = currentCatalog.publicId;
    document.getElementById("catalogAbility").value = currentCatalog.abilityId;
    document.getElementById("catalogLink").value = currentCatalog.link;

    if (currentCatalog.ambient) {
        document.getElementById("catalogAmbientBox").classList.remove("d-none");
        document.getElementById("catalogAmbient").value = currentCatalog.ambient;
    } else {
        document.getElementById("catalogAmbientBox").classList.add("d-none");
    }

    if (currentCatalog.approach) {
        document.getElementById("catalogApproachBox").classList.remove("d-none");
        document.getElementById("catalogApproach").value = currentCatalog.approach;
    } else {
        document.getElementById("catalogApproachBox").classList.add("d-none");
    }
}

async function updateCatalog(btn) {
    const data = getFormData("editCatalogForm");

    if (typeof data === "undefined") {
        alert("Preencha todos os campos");
        return;
    }

    if (data['categoryId'] != "2") {
        data['ambiente'] = null;
        data['abordagem'] = null;
    }

    delete data['catalogDeleteInput'];

    btn.disabled = false;
    btn.textContent = 'SALVANDO...';

    fetch(`${apiUrl}/catalog/update.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    }).then((resp) => resp.json())
        .then((resp) => {
            btn.disabled = false;
            btn.textContent = 'Salvar Alterações';
            if (!resp['success']) {
                alert(resp['msg']);
                return;
            }
            document.getElementById(`catalogTitle-${data['catalogId']}`).textContent = data['titulo'];
            alert('Atualização bem sucedida!');
        }).catch((err) => {
            btn.disabled = false;
            btn.textContent = 'Salvar Alterações';
            alert('Erro desconhecido!');
        });
}

async function insertCatalog(formId, categoryId, btn) {
    const data = getFormData(formId);

    if (typeof data === "undefined") {
        alert("Preencha todos os campos");
        return;
    }

    data['categoria'] = categoryId;
    data['userId'] = userData.userId;

    btn.disabled = true;
    btn.textContent = 'ENVIANDO...';

    return fetch(`${apiUrl}/catalog/new.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    }).then((resp) => resp.json())
        .then((resp) => {
            btn.disabled = false;
            btn.textContent = 'ENVIAR';
            if (!resp['success']) {
                alert(resp['msg']);
                return;
            }
            alert('Cadastro bem sucedido!');
        }).catch((err) => {
            btn.disabled = false;
            btn.textContent = 'ENVIAR';
            alert('Erro desconhecido!');
        });
}

async function deleteCatalog() {
    const catalogId = document.getElementById("catalogId").value;

    fetch(`${apiUrl}/catalog/delete.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 'catalogId': catalogId })
    }).then((resp) => resp.json())
        .then(async (resp) => {
            if (!resp['success']) {
                alert(resp['msg']);
                return;
            }

            alert("Catálogo deletado com sucesso");

            document.getElementById(`catalog_${catalogId}`).remove();

            const catalogsQuantity = document.getElementsByClassName("main__section-catalog").length;
            if (catalogsQuantity === 0) {
                document.getElementById("catalogsList").innerHTML = "<h3 style='transform: translateY(-2.5rem)' class='u-text-muted--2 d-flex align-items-center text-center h-100 mb-0'>Você não possui catálogos cadastrados</h3>"
            }
            document.getElementById("catalogsQuantity").textContent = catalogsQuantity;


            $("#editCatalogModal").modal('hide');
        }).catch((err) => {
            alert('Erro desconhecido!');
        });
}
