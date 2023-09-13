const apiUrl = 'http://localhost/sippec/api';

window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btnSendArticle')?.addEventListener('click', () => {
        const data = getFormData();
        if (!data) return;
        data['categoria'] = 1;
        newCatalog(data);
    });
    document.getElementById('btnSendGame')?.addEventListener('click', () => {
        const data = getFormData();
        if (!data) return;
        data['categoria'] = 2;
        newCatalog(data);

    });
    document.getElementById('btnSendMethod')?.addEventListener('click', () => {
        const data = getFormData();
        if (!data) return;
        data['categoria'] = 3;
        newCatalog(data);
    });
})

function inputIsValid(input) {
    const inputType = input.type;
    console.log(inputType)
    switch (inputType) {
        case 'text':
        case 'email':
        case 'url': {
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

function getFormData() {
    const form = document.forms['content-form'];
    const formInputs = form.elements;
    const data = {};

    for (let i = 0; i < formInputs.length; i++) {
        const input = formInputs[i];
        if (!inputIsValid(input)) {
            input.reportValidity();
            return data;
        };

        data[input.name] = input.value;
    }

    return data;
}

async function newCatalog(data) {
    console.log('adadsad')
    fetch(`${apiUrl}/catalog/new.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    }).then((resp) => resp.json())
        .then((resp) => {
            if (!resp['success']) {
                alert(resp['msg']);
            }
        }).catch((err) => {
            alert('Erro desconhecido!');
        })
}