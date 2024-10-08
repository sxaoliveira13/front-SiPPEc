

window.addEventListener('DOMContentLoaded', () => {
    clearAllInputs();
});

async function registerUser(btn) {
    const data = getFormData('registerForm');

    if (typeof data === "undefined") return;

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!validatePattern(data.userEmail, emailPattern)) {
        error('Por favor, insira um email válido.');
        return;
    }

    let cleanedPhone = data.userPhone.replace(/[^\d]/g, '');
    if (!/^\d{11}$/.test(cleanedPhone)) {
        error('Telefone inválido. Informe um número correto.');
        return;
    }

    if (data['userPassword'] !== data['userConfirmePassword']) {
        error("As senhas não são iguais!");
        return;
    }

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
    btn.textContent = 'CADASTRANDO...';

    await fetch(`${apiUrl}/user/register.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    }).then((resp) => resp.json())
        .then((resp) => {
            btn.textContent = 'Criar Conta';

            if (!resp['success']) {
                btn.disabled = false;
                error(resp['msg']);
                return;
            }

            success("Cadastro bem sucedido!");

            setTimeout(() => {
                window.location.href = `${systemUrl}awaitingApproval.php`;
            }, 1000);
        }).catch((err) => {
            btn.disabled = false;
            btn.textContent = 'Criar Conta';
            error("Erro ao tentar cadastrar!");
        });
}