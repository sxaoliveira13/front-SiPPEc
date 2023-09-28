window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btnSendLogin')?.addEventListener('click', (e) => {
        sendLogin(e.target);
    });
})

async function sendLogin(btn) {
    data = {};
    data['userLogin'] = document.forms['loginForm']['userLogin'].value;
    data['userPassword'] = document.forms['loginForm']['userPassword'].value;
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
            //Redirecionar para a página do formulário
        }).catch((err) => {
            alert(err);
        });
}
