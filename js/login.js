window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btnSubmitLogin')?.addEventListener('click', (e) => {
        checkLogin(e.target);
    });
});

async function checkLogin(btn) {
    const data = getFormData('loginForm');

    if (typeof data === "undefined") return;

    if (data['userEmail'].length === 0) {
        error('Informe um email!');
        return;
    }

    if (data['userPassword'].length === 0) {
        error('Informe uma senha!');
        return;
    }

    btn.disabled = true;
    btn.textContent = 'CARREGANDO...';

    fetch(`${apiUrl}/user/login.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    }).then((resp) => resp.json())
        .then((resp) => {
            btn.disabled = false;
            btn.textContent = 'Acessar Conta';

            if (!resp['success']) {
                error(resp['msg']);
                return;
            }

            window.location.href = `${systemUrl}catalog.php`;
        }).catch((err) => {
            btn.disabled = false;
            btn.textContent = 'Acessar Conta';
            error("Falha ao tentar fazer login");
        });
}


function forgotMyPassword() {
    document.getElementById('authBox').classList.toggle('d-none');
    document.getElementById('recoverPasswordBox').classList.toggle('d-none');
}

function recoverPassword(btn) {
    const data = getFormData('recoverPasswordForm');

    if (typeof data === "undefined") return;

    btn.setAttribute('disabled', '');
    btn.textContent = 'Enviando código...';

    fetch(`${apiUrl}/user/newPasswordRecover.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    }).then((resp) => resp.json())
        .then((resp) => {
            btn.textContent = 'Enviar código';

            if (!resp['success']) {
                btn.removeAttribute('disabled');
                error(resp['msg']);
                return;
            }

            if (!resp['data']['token']) {
                error("Falha ao gerar código de recuperação.");
                return;
            }

            success("Código enviado com sucesso");

            setTimeout(() => {
                window.location.href = `${systemUrl}recoverPassword.php?t=${resp['data']['token']}`;
            }, 1000);
        }).catch((err) => {
            btn.removeAttribute('disabled');
            btn.textContent = 'Enviar código';
            error("Falha ao tentar enviar código");
        });
}