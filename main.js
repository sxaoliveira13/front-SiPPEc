const apiUrl = 'http://localhost/sippec/api';

window.addEventListener('DOMContentLoaded', () => {
    document.getElementById("userName").textContent = userData.userName;

    document.getElementById('btnNewArticle')?.addEventListener('click', (e) => {
        newCatalog("articleForm", 1, e.target);
    });

    document.getElementById('btnNewGame')?.addEventListener('click', (e) => {
        newCatalog("gameForm", 2, e.target);
    });

    document.getElementById('btnNewMethod')?.addEventListener('click', (e) => {
        newCatalog("methodForm", 3, e.target);
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

async function newCatalog(formId, categoryId, btn) {
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