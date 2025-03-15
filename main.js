const locks = {};

let promiseResolveLoad;
locks['onload'] = new Promise(function (resolve, reject) {
    promiseResolveLoad = resolve;
});

let promiseUserLoad;
locks['user'] = new Promise(function (resolve, reject) {
    promiseUserLoad = resolve;
});

let promiseHeaderLoad;
locks['header'] = new Promise(function (resolve, reject) {
    promiseHeaderLoad = resolve;
});

class CustomError extends Error {
    constructor(message, code) {
        super(message);
        this.code = code;
        this.name = "CustomError";
    }
}

const catalogsDict = {
    '1': 'Artigo',
    '2': 'Jogo',
    '3': 'Método'
}
const catalogStatusDict = {
    '1': 'cadastro pendente',
    '2': 'cadastro aprovada',
    '3': 'cadastro recusada',
    '4': 'atualização pendente',
    '5': 'atualização aprovada',
    '6': 'atualização recusada',
    '7': 'exclusão pendente',
    '8': 'exclusão aprovada',
    '9': 'exclusão recusada'
};
const catalogStatusColorsDict = {
    'pendente': 'yellow',
    'aprovada': 'green',
    'recusada': 'red',
}

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

function togglePasswordVisibility(eyeBox) {
    const input = eyeBox.parentElement.getElementsByTagName('input')[0];

    const eyes = {
        'closedEye': `
        <svg class="form-group__input-icon form-group__input-icon--right" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.54199 1L19.542 19M8.38627 7.91364C7.86363 8.4536 7.54199 9.1892 7.54199 10C7.54199 11.6569 8.88517 13 10.542 13C11.3645 13 12.1097 12.669 12.6516 12.133M5.04199 4.64715C3.14269 5.90034 1.69602 7.78394 1 10C2.27425 14.0571 6.06456 17 10.5422 17C12.5311 17 14.3844 16.4194 15.9418 15.4184M9.54197 3.04939C9.87097 3.01673 10.2047 3 10.5422 3C15.0199 3 18.8102 5.94291 20.0844 10C19.8037 10.894 19.4007 11.7338 18.8952 12.5" stroke="#686868" stroke- width="1.7" stroke - linecap="round" stroke - linejoin="round"/>
        </svg>`,
        'openedEye': `
        <svg xmlns="http://www.w3.org/2000/svg"  fill="none" class="form-group__input-icon form-group__input-icon--right" viewBox="0 0 21 20"><g stroke="#686868" stroke- width="1.5" stroke - linecap="round" stroke - linejoin="round"><path d="M15 12c0 1.6569-1.3431 3-3 3s-3-1.3431-3-3 1.3431-3 3-3 3 1.3431 3 3Z"/><path stroke-linejoin="round" d="M6.94975 7.05025c2.73367-2.73367 7.16585-2.73367 9.89945 0l2.1214 2.12132c1.3333 1.33333 2 2.00003 2 2.82843 0 .8284-.6667 1.4951-2 2.8284l-2.1214 2.1213c-2.7336 2.7337-7.16578 2.7337-9.89945 0l-2.12132-2.1213c-1.33334-1.3333-2-2-2-2.8284 0-.8284.66666-1.4951 2-2.82843l2.12132-2.12132Z"/></g></svg>`
    };

    switch (input.type.toLowerCase()) {
        case 'text': {
            input.type = 'password';
            eyeBox.innerHTML = eyes['closedEye'];
            break;
        }
        case 'password': {
            input.type = 'text';
            eyeBox.innerHTML = eyes['openedEye'];
            break;
        }
    }
}

function search(containerId, searchText) {
    const container = document.getElementById(containerId);
    const searchElements = container.querySelectorAll('.searchable');
    searchText = searchText.toLowerCase().trim();

    if (searchText.length === 0) {
        searchElements.forEach((el) => el.classList.remove('d-none'));
        container.getElementsByClassName('search-result-text')[0]?.remove();
        return;
    }

    let occurrenceCount = 0;

    searchElements.forEach((el) => {
        const searchableText = el.querySelector('.searchableText').textContent;

        if (searchableText.includes(searchText)) {
            el.classList.remove('d-none');
            occurrenceCount++;
        } else {
            el.classList.add('d-none');
        }
    });

    searchResultsMessage(container, occurrenceCount);
}

function searchResultsMessage(container, occurrenceCount) {
    let msg = '';
    if (!occurrenceCount) {
        msg = 'Nenhum resultado encontrado para sua pesquisa';
    } else {
        msg = `Encontramos ${occurrenceCount} resultado${occurrenceCount > 1 ? 's' : ''} para sua pesquisa`;
    }

    const html = `  <div class="search-result-text border-bottom mb-3 pb-4">
    <h3 style="line-height: 1.7" class="u-text-muted--2 fs-4 text-center mb-0">${msg}</h3></div>`;
    container.getElementsByClassName('search-result-text')[0]?.remove();
    container.insertAdjacentHTML('afterbegin', html);
}

function validatePattern(value, pattern) {
    return pattern.test(value);
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

function getUrlParameter(parameter, remove = 0) {
    const url = new URL(window.location.href);
    const urlParams = new URLSearchParams(url.search);
    if (remove) {
        url.searchParams.delete(parameter);
        window.history.replaceState({}, document.title, url.toString());
    }
    return urlParams.get(parameter);
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
        append = `< b style="display: none" > !${inputTime.getTime() / 1000
            }?</ > `;
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