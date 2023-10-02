const apiUrl = 'http://localhost/sippec/api';

window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btnSendArticle')?.addEventListener('click', (e) => {
        sendData(e.target, 1);
    });

    document.getElementById('btnSendGame')?.addEventListener('click', (e) => {
        sendData(e.target, 2);
    });

    document.getElementById('btnSendMethod')?.addEventListener('click', (e) => {
        sendData(e.target, 3);
    });

    document.getElementById('btnLogout')?.addEventListener('click', (e) => {
        logout();
    });
})

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

async function sendData(btn, categoryId) {
    const data = getFormData('content-form');
    if (!data) return;
    data['categoria'] = categoryId;
    btn.disabled = true;
    btn.textContent = 'CARREGANDO...';
    await newCatalog(data);
    btn.disabled = false;
    btn.textContent = 'ENVIAR';
}

function getFormData(target) {
    const form = document.forms[target];
    const formInputs = form.elements;
    const data = {};

    for (let i = 0; i < formInputs.length; i++) {
        const input = formInputs[i];
        if (input.type === 'button') continue;

        if (!inputIsValid(input)) {
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

async function newCatalog(data) {
    return fetch(`${apiUrl}/catalog/new.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    }).then((resp) => resp.json())
        .then((resp) => {
            if (!resp['success']) {
                alert(resp['msg']);
                return;
            }
            alert('Cadastro bem sucedido!');
        }).catch((err) => {
            alert('Erro desconhecido!');
        });
}