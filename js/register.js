window.addEventListener('DOMContentLoaded', () => {
    clearAllInputs();
});

async function registerUser(btn) {
    const data = getFormData('registerForm');

    if (typeof data === "undefined") return;

    if (data['userPassword'] !== data['userConfirmePassword']) {
        warning("As senhas não são iguais!");
        return;
    }

    data['userType'] = 1;

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
            if (!resp['success']) {
                error(resp['msg']);
                return;
            }

            success("Cadastro bem sucedido!");

            setTimeout(() => {
                window.location.href = `${systemUrl}awaitingApproval.php`;
            }, 1200);
        }).catch((err) => {
            error("Erro ao tentar cadastrar!");
        });

    btn.disabled = false;
    btn.textContent = 'Criar Conta';
}