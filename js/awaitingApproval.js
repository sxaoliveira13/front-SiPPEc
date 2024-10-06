async function buildAwaitingInfos() {
    await locks['user'];
    document.getElementById('loader').remove();

    const msgByType = {
        '0': {
            'statusLabel': 'Aguardando Aprovação',
            'statusMessage': `Seu cadastro foi concluído e aguarda revisão do administrador do sistema. Você receberá um <span class="text-primary fw-bolder">email de confirmação</span> quando seu cadastro for aprovado.`
        },
        '3': {
            'statusLabel': 'Cadastro Negado',
            'statusMessage': 'Seu cadastro foi negado pelo administrador do sistema. Entre em contato pelo email <span class="text-primary fw-bolder">email@gmail.com</span> para mais informações.'
        }
    }

    const userType = userData['userType'];
    document.getElementById('registerStatusLabel').innerHTML = msgByType[userType]['statusLabel'];
    document.getElementById('registerStatusMessage').innerHTML = msgByType[userType]['statusMessage'];
    document.getElementById('registerStatusBox').classList.remove('d-none');
    document.getElementById('btnLogout').classList.toggle('d-none', userType !== '3');
}