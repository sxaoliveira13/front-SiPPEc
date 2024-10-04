const apiUrl = 'http://localhost/sippec/api';

var locks = {};

var promiseResolveLoad;
locks['onload'] = new Promise(function (resolve, reject) {
    promiseResolveLoad = resolve;
});

var promiseUserLoad;
locks['user'] = new Promise(function (resolve, reject) {
    promiseUserLoad = resolve;
});

class CustomError extends Error {
    constructor(message, code) {
        super(message);
        this.code = code;
        this.name = "CustomError";
    }
}

const catalogsDict = { '1': 'Artigo', '2': 'Jogo', '3': 'Método' }
const catalogSolicitationsDict = { '0': 'ativo', '1': 'cadastro pendente', '2': 'cadastro aprovado', '3': 'cadastro recusado', '4': 'atualização pendente', '5': 'atualização aprovada', '6': 'atualização recusada', '7': 'exclusão pendente', '8': 'exclusão aprovada', '9': 'exclusão recusada' };

window.addEventListener("DOMContentLoaded", () => {
    promiseResolveLoad();
    clearAllInputs();
    buildMasks();
});

async function logout() {
    return fetch(`${apiUrl}/user/logout.php`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    }).then((resp) => resp.json())
        .then((resp) => {
            if (!resp['success']) {
                error("Erro ao tentar desconectar");
                // console.log(resp["message"] ?? "");
                return;
            }
            window.location.href = 'login.php';
        }).catch((err) => {
            error("Erro ao tentar desconectar");
            // console.log(err);
        });
}

function getFormData(target) {
    const form = document.forms[target];
    const formInputs = form.elements;
    const data = {};

    for (let i = 0; i < formInputs.length; i++) {
        const input = formInputs[i];
        if (input.type === 'button') continue;

        if (!inputIsValid(input)) return;
        data[input.name] = input.value;
    }

    return data;
}

function inputIsValid(input) {
    const inputType = input.type;
    switch (inputType) {
        case 'select-one': {
            if (input.value === '-1') {
                warning(`Selecione uma opção para o campo "${input.name}"`);
                input.reportValidity();
                return false;
            }
            break;
        }
        default: {
            if (!input.checkValidity()) {
                warning(`Campo inválido!`);
                input.reportValidity();
                return false;
            }
        }
    }

    return true;
}

function clearAllInputs() {
    const inputs = document.querySelectorAll('input');
    const textareas = document.querySelectorAll('textarea');
    const selects = document.querySelectorAll('select');

    inputs.forEach(input => {
        if (input.type === 'checkbox' || input.type === 'radio') {
            input.checked = false;
        } else {
            input.value = '';
        }
    });

    textareas.forEach(textarea => {
        textarea.value = '';
    });

    selects.forEach(select => {
        select.selectedIndex = 0;
    });
}

function error(msg, timeout = 2500) {
    handlerAlertMessage('error', msg, timeout);
}
function success(msg, timeout = 2500) {
    handlerAlertMessage('success', msg, timeout);
}
function warning(msg, timeout = 2500) {
    handlerAlertMessage('warning', msg, timeout);
}

function handlerAlertMessage(type, msg, timeout = 2500) {
    if (!document.getElementById('alertMessageList')) {
        document.body.insertAdjacentHTML('beforeend', '<ul id="alertMessageList" class="alert-message-list list-unstyled m-0 p-0">\n' +
            '</ul>');
    }

    let alertMessageList = document.getElementById('alertMessageList');

    const typeClass = { 'success': 'success-alert', 'warning': 'warning-alert', 'error': 'error-alert' };

    let id = Date.now().toString();

    while (document.getElementById(id) !== null) {
        id = Date.now().toString();
    }

    const html = `<li id="${id}" class="alert-message-item show ${typeClass[type]}">
        <div class="w-100 d-flex flex-nowrap justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="assets/img/${type}.svg" alt="${type}" class="alert-msg-icon img-fluid me-3"">
                <p class="alert-msg my-0 pe-2">${msg}</p>
            </div>
            <img src="assets/img/close.svg" alt="${type}" class="alert-close-icon img-fluid">
        </div>
    </li>`;

    alertMessageList.insertAdjacentHTML('beforeend', html);
    alertMessageList.style.visibility = 'visible';

    let currentAlertItem = document.getElementById(id);

    const hiddenAlert = () => {
        if (!document.getElementById(id)) return;
        currentAlertItem.classList.remove('show');
        currentAlertItem.classList.add('hidden');

        currentAlertItem.addEventListener('animationend', () => {
            currentAlertItem.remove();

            if (alertMessageList.children.length < 1) {
                alertMessageList.style.visibility = 'hidden';
            }
        });
    }

    currentAlertItem.addEventListener('animationend', () => {
        setTimeout(() => {
            hiddenAlert();
        }, timeout);
    });

    currentAlertItem.addEventListener('click', () => {
        hiddenAlert();
    });
}

function forceRedirect(url) {
    url = systemUrl + url;
    window.location.href = url;
}

function forceRedirectByUserType(type) {
    switch (type) {
        case "0":
        case "3": {
            if (currentPage !== 'awaitingApproval') {
                forceRedirect('awaitingApproval.php?r=true');
            }
            break;
        }
        case "1":
        case "2": {
            if (currentPage === 'awaitingApproval') {
                forceRedirect('catalog.php');
            }
            break;
        }
    }
}

function forceRedirectByError(code) {
    switch (code) {
        case 1: {
            forceRedirect('login.php');
            break;
        }
    }
}

async function buildMasks() {
    if (typeof $().mask != "function") {
        await sleep(2000);
    }

    if (typeof $().mask == "function") {
        $('.phoneMask').mask('(00) 0000-00000');
    }
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function formatDate(time, short = false, epoch = false) {
    return returnDate(time, short, epoch);
}

function returnDate(time, short = false, epoch = false) {
    var inputTime = new Date(time);
    let append = '';
    if (epoch) {
        append = `<b style="display: none">!${inputTime.getTime() / 1000}?</b>`;
    }

    var day = ('0' + inputTime.getDate()).slice(-2);
    var month = ('0' + (inputTime.getMonth() + 1)).slice(-2);
    var year = inputTime.getFullYear();
    var hour = ('0' + inputTime.getHours()).slice(-2);
    var minute = ('0' + inputTime.getMinutes()).slice(-2);
    if (short) {
        return append + day + '/' + month + '/' + year;
    }
    return append + day + '/' + month + '/' + year + ' às ' + hour + ':' + minute;
}

function beautifyDate(datetimeString, options = {}) {
    const date = new Date(datetimeString);
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const seconds = date.getSeconds().toString().padStart(2, '0');

    const longMonthNames = [
        "janeiro", "fevereiro", "março", "abril", "maio", "junho",
        "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"
    ];

    let formattedDate = '';
    if (options.full) {
        formattedDate = `${day} de ${longMonthNames[date.getMonth()]} de ${year}`;
    } else {
        formattedDate = `${day}/${month}/${year}`;
    }

    if (options.withTime) {
        formattedDate += ` ${hours}:${minutes}:${seconds}`;
    }

    return formattedDate;
}