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

window.addEventListener("DOMContentLoaded", () => {
    clearAllInputs();
    promiseResolveLoad();
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
                alert(resp['msg']);
                return;
            }
            window.location.href = 'login.php';
        }).catch((err) => {
            alert('Erro desconhecido!');
        });
}


function getFormData(target) {
    const form = document.forms[target];
    const formInputs = form.elements;
    const data = {};

    for (let i = 0; i < formInputs.length; i++) {
        const input = formInputs[i];
        if (input.type === 'button') continue;

        if (!inputIsValid(input)) {
            console.log(input)
            input.reportValidity();
            return;
        };

        data[input.name] = input.value;
    }

    return data;
}

function inputIsValid(input) {
    const inputType = input.type;
    switch (inputType) {
        case 'select-one': {
            if (input.value === '-1') {
                alert('Selecione uma opção para o campo ' + input.name);
                return false;
            }
            break;
        }
        default: {
            if (!input.checkValidity()) return false;
        }
    }

    return true;
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

function clearAllInputs() {
    const inputs = document.querySelectorAll('input');
    const textareas = document.querySelectorAll('textarea');
    const selects = document.querySelectorAll('select');

    inputs.forEach(input => {
        if (input.type === 'checkbox' || input.type === 'radio') {
            input.checked = false;
        } else {
            console.log(input)
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

// Chame a função quando necessário
clearAllInputs();
