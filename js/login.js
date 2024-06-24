window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btnSubmitLogin')?.addEventListener('click', (e) => {
        checkLogin(e.target);
    });
});

async function checkLogin(btn) {
    const data = getFormData('loginForm');

    if (typeof data === "undefined") return;

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
            return;
        }).catch((err) => {
            btn.disabled = false;
            btn.textContent = 'Acessar Conta';
            error("Falha ao tentar fazer login");
        });
}
