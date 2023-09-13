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
})

async function sendData(btn, categoryId) {
    const data = getFormData();
    if (!data) return;
    data['categoria'] = categoryId;
    btn.disabled = true;
    btn.textContent = 'CARREGANDO...';
    await newCatalog(data);
    btn.disabled = false;
    btn.textContent = 'ENVIAR';
}

function getFormData() {
    const form = document.forms['content-form'];
    const formInputs = form.elements;
    const data = {};

    for (let i = 0; i < formInputs.length; i++) {
        const input = formInputs[i];
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
        case 'text':
        case 'email':
        case 'url':
        case 'number':
        case 'textarea': {
            if (!input.checkValidity()) {
                return false;
            }
            break;
        }
        case 'select-one': {
            if (input.value === '-1') {
                alert('fazer com que apareça uma mensagem dizendo para selecionar alguma opção');
                return false;
            }
            break;
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