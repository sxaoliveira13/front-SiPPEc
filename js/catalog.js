let numberOfNewCatalogs = 0;
let numberOfNewUsers = 0;

let newCatalogsData;
let newUsersData;

let currentCatalog = {};

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
        fetchNewUsers();
        fetchNewCatalogs();
    }
}

var dataTableObjNewUsers = false;
async function fetchNewUsers() {
    await fetch(`${apiUrl}/user/getNew.php`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    }).then((resp) => resp.json())
        .then(async (resp) => {
            await locks['onload'];

            if (!resp['success']) {
                throw new CustomError(resp['msg'], resp['code']);
            }

            newUsersData = resp['data'];
            numberOfNewUsers = resp['data'].length;

            if (resp['data'].length > 0) {
                document.getElementById('newUsersQuantity').style.display = 'flex';
                document.getElementById('newUsersQuantity').textContent = numberOfNewUsers;
            } else {
                document.getElementById('newUsersQuantity').setAttribute('style', 'display: none !important');
            }

            if ((numberOfNewUsers + numberOfNewCatalogs) > 0) {
                document.getElementById('totalSolicitationsQuantity').style.opacity = '1';
                document.getElementById('totalSolicitationsQuantity').textContent = numberOfNewUsers + numberOfNewCatalogs;
            } else {
                document.getElementById('totalSolicitationsQuantity').style.opacity = '0';
            }

            if (dataTableObjNewUsers !== false) {
                dataTableObjNewUsers.destroy();
            }

            dataTableObjNewUsers = $('.datatableNewUserRegisters').DataTable({
                "data": resp['data'].map(function (c) {
                    let actionButtons = `
                        <div class="d-flex align-items-center justify-content-center">
                            <img onclick="approveNewUser(${c['id']}, 3)" src="assets/img/close.svg" style="width: 2.6rem;" alt="recusar" class="alert-close-icon img-fluid u-cursor-pointer u-filter--red me-2">
                            <img onclick="approveNewUser(${c['id']}, 1)" style="width: 2.4rem;" src="assets/img/success2.svg" alt="aprovar" class="alert-close-icon img-fluid u-cursor-pointer u-filter--green ms-2">
                        </div>
                    `;
                    return [c['name'], `<a href="https://wa.me/${c['phone']}">${c['phone']}</a>`, c['email'], formatDate(c['createTime']), actionButtons];
                }),
            });
        }).catch((err) => {
            if (err instanceof CustomError) {
                error(err['message']);
                forceRedirectByError(err['code']);
            } else {
                error(err);
            }
        });
}

var dataTableObjNewCatalogs = false;
async function fetchNewCatalogs() {
    await fetch(`${apiUrl}/catalog/getNewSolicitations.php`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    }).then((resp) => resp.json())
        .then(async (resp) => {
            await locks['onload'];

            if (!resp['success']) {
                throw new CustomError(resp['msg'], resp['code']);
            }

            newCatalogsData = resp['data'];
            numberOfNewCatalogs = resp['data'].length;

            if (resp['data'].length > 0) {
                document.getElementById('newCatalogsQuantity').style.display = 'flex';
                document.getElementById('newCatalogsQuantity').textContent = numberOfNewCatalogs;
            } else {
                document.getElementById('newCatalogsQuantity').setAttribute('style', 'display: none !important');
            }

            if ((numberOfNewUsers + numberOfNewCatalogs) > 0) {
                document.getElementById('totalSolicitationsQuantity').style.opacity = '1';
                document.getElementById('totalSolicitationsQuantity').textContent = numberOfNewUsers + numberOfNewCatalogs;
            } else {
                document.getElementById('totalSolicitationsQuantity').style.opacity = '0';
            }

            if (dataTableObjNewCatalogs !== false) {
                dataTableObjNewCatalogs.destroy();
            }

            dataTableObjNewCatalogs = $('.datatableNewCatalogs').DataTable({
                "data": resp['data'].map(function (c) {
                    let actionButton = `<button onclick="buildCatalogSolicitationModal(${c['catalogId']});" class="fs-5 w-auto button-primary px-4 py-2 ms-auto">Ver</button>`;
                    return [c['title'], catalogsDict[c['categoryId']], `<span class="text-capitalize">${catalogStatusDict[c['status']]}</span>`, c['userName'], c['createdAt'], actionButton];
                }),
            });
        }).catch((err) => {
            if (err instanceof CustomError) {
                error(err['message']);
                forceRedirectByError(err['code']);
            } else {
                error(err);
            }
        });
}

async function buildCatalogSolicitationModal(catalogId) {
    $("#catalogSolicitationModal").modal('show');

    currentCatalog = newCatalogsData.find(catalog => catalog.catalogId == catalogId);

    document.getElementById('catalogSolicitationType').textContent = catalogStatusDict[currentCatalog['status']];
    document.getElementById('catalogInternalTitle').textContent = currentCatalog['title'];
    document.getElementById('catalogPublicName').textContent = currentCatalog['publicName'];
    document.getElementById('catalogContentType').textContent = currentCatalog['content'];
    document.getElementById('catalogToolName').textContent = currentCatalog['toolName'];
    document.getElementById('catalogAbilityName').textContent = currentCatalog['abilityName'];
    document.getElementById('catalogAccessLink').textContent = currentCatalog['link'];
    document.getElementById('catalogAccessLink').href = currentCatalog['link'];
    document.getElementById('catalogApproveMessage').value = '';
    document.getElementById('catalogType').textContent = catalogsDict[currentCatalog['categoryId']];
    document.getElementById('catalogIsActive').textContent = currentCatalog['active'] ? 'Sim' : 'Não';

    if (currentCatalog['ambient']) {
        document.getElementById('newCatalogAmbient').textContent = currentCatalog['ambient'];
    }
    if (currentCatalog['approach']) {
        document.getElementById('newCatalogApproach').textContent = currentCatalog['approach'];
    }

    console.log(currentCatalog['ambient'] ? false : true)
    document.getElementById('newCatalogAmbient')
        .parentElement.classList.toggle('d-none', currentCatalog['ambient'] ? false : true);
    document.getElementById('newCatalogApproach')
        .parentElement.classList.toggle('d-none', currentCatalog['approach'] ? false : true);

    const userInfo = await getFullUserInfo(currentCatalog['userId']);

    if (userInfo && userInfo.user) {
        document.getElementById('userFullName').textContent = userInfo.user.name || 'Não disponível';
        document.getElementById('userMail').textContent = userInfo.user.email || 'Não disponível';
        document.getElementById('userPhone').textContent = userInfo.user.phone || 'Não disponível';
        document.getElementById('userCreateTime').textContent = formatDate(userInfo.user.createTime) || 'Não disponível';
        document.getElementById('userLastAccess').textContent = formatDate(userInfo.user.lastAccess) || 'Não disponível';
    }

    if (userInfo && userInfo.catalogs) {
        const catalogCounter = userInfo.catalogs.map((catalog) => {
            return catalog.catalogCount;
        });

        document.getElementById('articlesCount').textContent = catalogCounter[0] ?? "0";
        document.getElementById('gamesCount').textContent = catalogCounter[1] ?? "0";
        document.getElementById('methodsCount').textContent = catalogCounter[2] ?? "0";
    }
}

async function approveCatalog(isApproved) {
    const catalogMessage = document.getElementById('catalogApproveMessage').value ?? '';

    await fetch(`${apiUrl}/catalog/approve.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            'catalogId': currentCatalog['catalogId'],
            'currentStatus': currentCatalog['status'],
            'isApproved': isApproved,
            'catalogMessage': catalogMessage
        }),
    }).then((resp) => resp.json())
        .then(async (resp) => {
            await locks['onload'];

            if (!resp['success']) {
                throw new CustomError(resp['msg'], resp['code']);
            }

            success(`Solicitação ${isApproved === 1 ? 'aprovada' : 'recusada'} com sucesso!`);
            $("#catalogSolicitationModal").modal('hide');
            fetchNewCatalogs();
        }).catch((err) => {
            if (err instanceof CustomError) {
                error(err['message']);
                forceRedirectByError(err['code']);
            } else {
                error("Erro ao tentar aprovar/rejeitar solicitação");
                error(err);
            }
        });
}

async function approveNewUser(userId, isApproved) {
    await fetch(`${apiUrl}/user/approve.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 'userId': userId, 'isApproved': isApproved }),
    }).then((resp) => resp.json())
        .then(async (resp) => {
            await locks['onload'];

            if (!resp['success']) {
                throw new CustomError(resp['msg'], resp['code']);
            }

            success(`Usuário ${isApproved === 1 ? 'aprovado' : 'recusado'} com sucesso!`);
            fetchNewUsers();
        }).catch((err) => {
            if (err instanceof CustomError) {
                error(err['message']);
                forceRedirectByError(err['code']);
            } else {
                error("Erro ao tentar aprovar/rejeitar usuário");
            }
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
    await fetch(`${apiUrl}/catalog/get.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 'userId': userData['userId'] })
    }).then((resp) => resp.json())
        .then(async (resp) => {
            await locks['onload'];

            if (!resp['success']) {
                throw new CustomError(resp['msg'], resp['code']);
            }

            document.getElementById("userCatalogsQuantity").textContent = resp['data'].length;

            resp['data'].forEach(catalog => {
                catalogs[catalog['catalogId']] = catalog;
            });


            buildCatalogsList(resp['data']);

            if (currentCatalog['catalogId'] && typeof currentCatalog['catalogId'] !== 'undefined') {
                buildEditCatalogModal(currentCatalog['catalogId']);
                console.log("adw")
            }
        }).catch((err) => {
            if (err instanceof CustomError) {
                error(err['message']);
                forceRedirectByError(err['code']);
            } else {
                error("Erro ao buscar catálogos!");
            }
        });
}

function buildCatalogsList(data) {
    const catalogsListEl = document.getElementById("catalogsList");
    catalogsListEl.innerHTML = "";

    if (data.length === 0) {
        catalogsListEl.innerHTML = `
            <h3 style="line-height: 1.7" class="u-text-muted--2 d-flex align-items-center justify-content-center text-center h-100 mt-3 mb-0">
                Você não possui catálogos cadastrados
            </h3>`;
        return;
    }

    data.forEach(catalog => {
        const { title, catalogId, lastUpdate, status } = catalog;
        const [action, statusLabel] = catalogStatusDict[status].split(' ');
        const statusColor = catalogStatusColorsDict[statusLabel];

        const html = `
            <li id="catalog_${catalogId}" class="main__section-catalog searchable">
                <div class="catalog__header">
                    <h3 id="catalogTitle-${catalogId}" class="catalog__header-title searchableText u-text-ellipsis u-text-ellipsis--2-lines pe-3 mb-0" title="${title}">${title}</h3>
                    <button class="catalog__header-button" onclick="buildEditCatalogModal(${catalogId})" data-bs-toggle="modal" data-bs-target="#editCatalogModal">Ver</button>
                </div>
                <div class="catalog__body">
                    <p class="my-3">Solicitação de ${action} <b class="u-text-color u-text-color--${statusColor}">${statusLabel}</b></p>
                </div>
                <div class="catalog__footer">
                    <p class="u-text-color u-text-color--muted mb-0">${formatDate(lastUpdate)}</p>
                </div>
            </li> 
        `;

        catalogsListEl.insertAdjacentHTML("beforeend", html);
    });
}

function buildEditCatalogModal(catalogId) {
    currentCatalog = catalogs[catalogId];

    const catalogStatus = catalogs[catalogId]['status'];
    const currentStatus = catalogStatusDict[catalogStatus].split(' ');
    const [statusType, statusText] = [currentStatus[0], currentStatus[1]];
    const statusColor = catalogStatusColorsDict[currentStatus[1]];

    document.getElementById('catalogCurrentStatus').classList.remove('u-bg--red', 'u-bg--green', 'u-bg--yellow');
    document.getElementById('catalogCurrentStatus').classList.add(`u-bg--${statusColor}`);
    document.getElementById('catalogCurrentStatusLabel').innerHTML = `Solicitação de ${statusType} ${statusText}</span>`;

    if (currentCatalog['lastMessage']) {
        document.getElementById('catalogCurrentMessage').classList.remove('d-none');
        document.getElementById('catalogCurrentMessage').innerHTML = `<p class="fs-4 text-white mb-0" style="font-weight: 500;">"${currentCatalog['lastMessage']}"</p>`;
    } else {
        document.getElementById('catalogCurrentMessage').classList.add('d-none');
    }

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

    buildEditCatalogModalButtons(catalogStatus);
}

function buildEditCatalogModalButtons(status) {
    document.getElementById('btnUpdateCatalog').classList.remove('d-none');
    document.getElementById('catalogDeleteInputBox').classList.remove('d-none');
    document.getElementById('btnRequestRegistrationReview').classList.add('d-none');
    document.getElementById('btnCatalogCancelDelete').classList.add('d-none');

    switch (status) {
        case '1':
        case '2':
        case '5':
        case '6': {
            document.getElementById('btnRequestRegistrationReview').classList.add('d-none');
            break;
        }
        case '3': {
            document.getElementById('btnUpdateCatalog').classList.add('d-none');
            document.getElementById('btnRequestRegistrationReview').classList.remove('d-none');
            break;
        }
        case '4': {
            document.getElementById('catalogDeleteInputBox').classList.add('d-none');
            break;
        }
        case '7': {
            document.getElementById('btnUpdateCatalog').classList.add('d-none');
            document.getElementById('catalogDeleteInputBox').classList.add('d-none');
            document.getElementById('btnCatalogCancelDelete').classList.remove('d-none');
        }
        case '9': {
            document.getElementById('btnUpdateCatalog').classList.add('d-none');
            document.getElementById('catalogDeleteInputBox').classList.add('d-none');
        }
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
    data['userId'] = userData['userId'];

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
            buildEditCatalogModal(data['catalogId']);
        }).catch((err) => {
            if (err instanceof CustomError) {
                error(err['message']);
                forceRedirectByError(err['code']);
            } else {
                error("Erro ao tentar atualizar catálogo!");
            }
            btn.disabled = false;
            btn.textContent = 'Salvar Alterações';
        });
}

async function requestCatalogRegistrationReview(btn) {
    const data = getFormData("editCatalogForm");

    if (typeof data === "undefined") return;

    if (data['categoryId'] != "2") {
        data['ambiente'] = null;
        data['abordagem'] = null;
    }

    delete data['catalogDeleteInput'];
    data['userId'] = userData['userId'];

    btn.disabled = false;
    btn.textContent = 'SALVANDO...';

    fetch(`${apiUrl}/catalog/reviewRegistration.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    }).then((resp) => resp.json())
        .then(async (resp) => {
            btn.disabled = false;
            btn.textContent = 'Solicitar revisão de cadastro';

            if (!resp['success']) {
                throw new CustomError(resp['msg', resp['code']]);
            }

            document.getElementById(`catalogTitle-${data['catalogId']}`).textContent = data['titulo'];
            success('Solicitação feita com sucesso!');
            await fetchUserCatalogs();
            buildEditCatalogModal(data['catalogId']);
        }).catch((err) => {
            if (err instanceof CustomError) {
                error(err['message']);
                forceRedirectByError(err['code']);
            } else {
                error("Erro ao tentar solicitar cadastro!");
            }
            btn.disabled = false;
            btn.textContent = 'Solicitar revisão de cadastro';
        });
}

async function showManageTab() {
    await locks['user'];
    if (typeof userData['userType'] != "undefined" && userData['userType'] === "2") {
        const tabButton = document.getElementById('manageSolicitations');
        tabButton.classList.remove('d-none');
    }
}

async function getFullUserInfo(userId) {
    return fetch(`${apiUrl}/user/getFull.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 'userId': userId })
    }).then((resp) => resp.json())
        .then((resp) => {
            if (!resp['success']) {
                throw new CustomError(resp['msg', resp['code']]);
            }
            return resp['data'];
        }).catch((err) => {
            if (err instanceof CustomError) {
                error(err['message']);
                forceRedirectByError(err['code']);
            } else {
                error("Erro ao tentar buscar informações do usuário");
            }
        });
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

            success("Solicitação feita com sucesso!");
            fetchUserCatalogs();
        }).catch((err) => {
            if (err instanceof CustomError) {
                error(err['message']);
                forceRedirectByError(err['code']);
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
            success("Solicitação feita com sucesso!");

            // $("#editCatalogModal").modal('hide');
        }).catch((err) => {
            if (err instanceof CustomError) {
                error(err['message']);
                forceRedirectByError(err['code']);
            } else {
                error("Erro ao tentar excluir catálogo!");
            }
        });
}
