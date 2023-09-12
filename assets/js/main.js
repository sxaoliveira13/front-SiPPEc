const apiUrl = 'http://localhost/sippec/api';

async function newCatalog() {
    fetch(`${apiUrl}/sippec/api/article/new.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({key1: 'value1', key2: 'value2'})
        .then((resp) => resp.json())
        .then((resp) => {
            console.log(resp);
        }).catch((err) => {
            console.error('Ocorreu um erro:', err.message);
        })
    });
}