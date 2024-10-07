function recoverPassword(btn) {
    const data = getFormData('recoverPasswordForm');

    if (typeof data === "undefined") return;

    data['token'] = token;

    btn.disabled = true;
    btn.textContent = 'Verificando código...';

    fetch(`${apiUrl}/user/checkRecoverPasswordCode.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    }).then((resp) => resp.json())
        .then((resp) => {
            btn.disabled = false;
            btn.textContent = 'Verificar código';

            if (!resp['success']) {
                error(resp['msg']);
                return;
            }

            success("Código verificado com sucesso!");

            setTimeout(() => {
                window.location.href = `${systemUrl}newPassword.php?t=${token}`;
            }, 1000);
        }).catch((err) => {
            btn.disabled = false;
            btn.textContent = 'Verificar código';
            error("Falha ao verificar código de recuperação.");
        });

}

async function registerNewPassword(btn) {
    const data = getFormData('newPasswordForm');

    if (typeof data === "undefined") return;

    data['token'] = token;

    if (data['userPassword'] !== data['userConfirmePassword']) {
        error("As senhas não são iguais!");
        return;
    }

    delete data['userConfirmePassword'];

    if (data.userPassword.length < 6) {
        error('A senha deve ter pelo menos 6 caracteres.');
        return;
    }

    if (!/[A-Z]/.test(data.userPassword)) {
        error('A senha deve incluir pelo menos uma letra maiúscula.');
        return;
    }
    if (!/[a-z]/.test(data.userPassword)) {
        error('A senha deve incluir pelo menos uma letra minúscula.');
        return;
    }
    if (!/[0-9]/.test(data.userPassword)) {
        error('A senha deve incluir pelo menos um número.');
        return;
    }
    if (!/[\W_]/.test(data.userPassword)) {
        error('A senha deve incluir pelo menos um caractere especial.');
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Atualizando senha...';

    await fetch(`${apiUrl}/user/newPassword.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    }).then((resp) => resp.json())
        .then((resp) => {
            if (!resp['success']) {
                error(resp['msg']);
                return;
            }

            success("Senha atualizada com sucesso!");

            setTimeout(() => {
                window.location.href = `${systemUrl}login.php`;
            }, 1000);
        }).catch((err) => {
            error("Erro ao tentar atualizar a senha.");
        });

    btn.disabled = false;
    btn.textContent = 'Criar Conta';
}