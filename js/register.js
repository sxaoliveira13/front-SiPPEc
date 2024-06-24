window.addEventListener('DOMContentLoaded', () => {
    clearAllInputs();
});

async function registerUser(btn) {
    const data = getFormData('registerForm');

    if (typeof data === "undefined") {
        alert("Preencha todos os campos");
        return;
    };

    if (data['userPassword'] !== data['userConfirmePassword']) {
        alert("As senhas não são iguais!");
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
                alert(resp['msg']);
                return;
            }
            window.location.href = `${systemUrl}catalog.php`;
            return;
        }).catch((err) => {

            alert(err);
        });

    btn.disabled = false;
    btn.textContent = 'Criar Conta';
}