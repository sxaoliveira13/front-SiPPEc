window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btnSendLogin')?.addEventListener('click', (e) => {
        sendLogin(e.target);
    });
})

async function sendLogin(btn) {
    const data = getFormData('login-form');
    btn.disabled = true;
    btn.textContent = 'CARREGANDO...';
    await verifyLogin(data);
    btn.disabled = false;
    btn.textContent = 'Login';
}

async function verifyLogin(data) {
    fetch(`${apiUrl}/user/login.php`, {
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
            window.location.href = `${systemUrl}novos_artigos.php`;
            return;
        }).catch((err) => {
            alert(err);
        });
}
