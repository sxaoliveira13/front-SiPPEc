window.addEventListener('DOMContentLoaded', () => {
    handleButtonsAndInputs();
    document.getElementById('editCatalogModal').addEventListener('hidden.bs.modal', () => {
        document.getElementById("catalogDeleteInput").value = "";
    });
});

$.extend($.fn.dataTable.defaults, {
    autoWidth: false,
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    language: {
        url: systemUrl + 'assets/json/datatable.pt-BR.json',
        search: '_INPUT_',
        searchPlaceholder: 'Pesquisar',
        lengthMenu: '<span>Exibir:</span> _MENU_',
        paginate: { 'first': 'Primeiro', 'last': 'Ultimo', 'next': '&rarr;', 'previous': '&larr;' }
    }
});



buildUserInfo();
async function buildUserInfo() {
    await Promise.all([locks['user'], locks['onload']]);
    document.getElementById("userName").textContent = userData['userName'];

    if (typeof userData['userType'] != "undefined" && userData['userType'] === "2") {
        fetchUserManage();
    }
}

var dataTableObj1 = false;
var dataTableObj2 = false;
async function fetchUserManage() {
    dataTableObj1 = $('.datatableNewCatalogs').DataTable({
        "data": [['Jogo da Velha', 'Jogo', 'Novo cadastro', 'Victor Osses', '25/08/2024', `ação`]].map(function (c) {
            return [c[0], c[1], c[2], c[3], c[4], c[5]];
        }),
    });


    dataTableObj2 = $('.datatableNewUserRegisters').DataTable({
        "data": [['Valdemar Costa Neto', '(19) 99868-5541', 'victor.costa.osses@gmail.com', '22/10/2024', 'ações'],
        ['Valdemar Costa Neto', '(19) 99868-5541', 'victor.costa.osses@gmail.com', '22/10/2024', 'ações'],
        ['Valdemar Costa Neto', '(19) 99868-5541', 'victor.costa.osses@gmail.com', '22/10/2024', 'ações'],
        ['Valdemar Costa Neto', '(19) 99868-5541', 'victor.costa.osses@gmail.com', '22/10/2024', 'ações'],
        ['Valdemar Costa Neto', '(19) 99868-5541', 'victor.costa.osses@gmail.com', '22/10/2024', 'ações'],
        ['Valdemar Costa Neto', '(19) 99868-5541', 'victor.costa.osses@gmail.com', '22/10/2024', 'ações'],
        ['Valdemar Costa Neto', '(19) 99868-5541', 'victor.costa.osses@gmail.com', '22/10/2024', 'ações'],
        ['Valdemar Costa Neto', '(19) 99868-5541', 'victor.costa.osses@gmail.com', '22/10/2024', 'ações'],
        ['Valdemar Costa Neto', '(19) 99868-5541', 'victor.costa.osses@gmail.com', '22/10/2024', 'ações'],
        ['Valdemar Costa Neto', '(19) 99868-5541', 'victor.costa.osses@gmail.com', '22/10/2024', 'ações'],
        ['Valdemar Costa Neto', '(19) 99868-5541', 'victor.costa.osses@gmail.com', '22/10/2024', 'ações'],
        ['Valdemar Costa Neto', '(19) 99868-5541', 'victor.costa.osses@gmail.com', '22/10/2024', 'ações'],
        ['Valdemar Costa Neto', '(19) 99868-5541', 'victor.costa.osses@gmail.com', '22/10/2024', 'ações'],
        ['Valdemar Costa Neto', '(19) 99868-5541', 'victor.costa.osses@gmail.com', '22/10/2024', 'ações']].map(function (c) {
            return [c[0], `<a href="https://wa.me/${c[1]}">${c[1]}</a>`, c[2], c[3], c[4]];
        }),
    });
}

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

    document.getElementById('btnLogout')?.addEventListener('click', () => {
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

    document.getElementById('btnDeleteCatalog')?.addEventListener('click', () => {
        deleteCatalog();
    });
}

let catalogs = {};
fetchUserCatalogs();
async function fetchUserCatalogs() {
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
                throw new CustomError(resp['msg'], resp['code']);
            }

            setTimeout(() => {
                document.getElementById("catalogsQuantity").textContent = resp['data'].length;
                buildCatalogsList(resp['data']);
            }, 700);
        }).catch((err) => {
            if (err instanceof CustomError) {
                error(err['message']);
                handleErrors(err['code']);
            } else {
                error("Erro ao buscar catálogos!");
            }
        });
}

function buildCatalogsList(data) {
    document.getElementById("catalogsList").innerHTML = "";

    if (data.length === 0) {
        document.getElementById("catalogsList").innerHTML = "<h3 style='line-height: 1.7' class='u-text-muted--2 d-flex align-items-center justify-content-center text-center h-100 mt-3 mb-0'>Você não possui catálogos cadastrados</h3>"
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

    if (typeof data === "undefined") return;

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
                throw new CustomError(resp['msg', resp['code']]);
            }

            document.getElementById(`catalogTitle-${data['catalogId']}`).textContent = data['titulo'];
            success('Catálogo atualizado com sucesso!');
            fetchUserCatalogs();
        }).catch((err) => {
            if (err instanceof CustomError) {
                error(err['message']);
                handleErrors(err['code']);
            } else {
                error("Erro ao tentar atualizar catálogo!");
            }
            btn.disabled = false;
            btn.textContent = 'Salvar Alterações';
        });
}

async function showManageTab() {
    await locks['user'];
    if (typeof userData['userType'] != "undefined" && userData['userType'] === "2") {
        const tabButton = document.getElementById('manageSolicitations');
        tabButton.classList.remove('d-none');
        // tabButton.getElementsByTagName('button')[0].click();
    }
}

async function insertCatalog(formId, categoryId, btn) {
    const data = getFormData(formId);

    if (typeof data === "undefined") return;

    data['categoria'] = categoryId;
    data['userId'] = userData.userId;

    const lastBtnText = btn.textContent;
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
            btn.textContent = lastBtnText;

            if (!resp['success']) {
                throw new CustomError(resp['msg', resp['code']]);
            }

            success("Catálogo adicionado com sucesso!");
            fetchUserCatalogs();
        }).catch((err) => {
            if (err instanceof CustomError) {
                error(err['message']);
                handleErrors(err['code']);
            } else {
                error("Erro ao tentar adicionar catálogo!");
            }
            btn.disabled = false;
            btn.textContent = lastBtnText;
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
                throw new CustomError(resp['msg', resp['code']]);
            }

            document.getElementById(`catalog_${catalogId}`).remove();

            fetchUserCatalogs();
            success("Catálogo excluído com sucesso!");

            $("#editCatalogModal").modal('hide');
        }).catch((err) => {
            if (err instanceof CustomError) {
                error(err['message']);
                handleErrors(err['code']);
            } else {
                error("Erro ao tentar excluir catálogo!");
            }
        });
}
