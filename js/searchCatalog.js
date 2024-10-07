$.extend($.fn.dataTable.defaults, {
    autoWidth: false,
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    language: {
        url: systemUrl + 'assets/json/datatable.pt-BR.json',
        search: '_INPUT_',
        searchPlaceholder: 'Pesquisar',
        lengthMenu: '<span>Exibir:</span> _MENU_',
        paginate: { 'first': 'Primeiro', 'last': 'Ultimo', 'next': '&rarr;', 'previous': '&larr;' }
    }
});

buildCatalogsDatatable();
var catalogsDatatableObj = false;
async function buildCatalogsDatatable() {
    await locks['onload'];

    document.getElementById('loader').classList.remove('d-none');

    const filters = getCatalogFilters();

    fetch(`${apiUrl}/catalog/search.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 'filters': filters })
    }).then((resp) => resp.json())
        .then((resp) => {
            if (!resp['success']) {
                error(resp['msg']);
                return;
            }

            document.getElementById('loader').classList.add('d-none');
            document.getElementById('searchDatatable').classList.remove('d-none');

            resp['data'].forEach(data => {
                delete data['categoryId'];
                delete data['active'];
                delete data['status'];
            });

            if (catalogsDatatableObj !== false) {
                catalogsDatatableObj.destroy();
            }

            console.log(resp['data'])

            catalogsDatatableObj = $('.datatableCatalogs').DataTable({
                "data": resp['data'].map(function (c) {
                    const link = `<a class="text-link" href="${c['link']}" target="_blank">${c['link']}</a>`
                    if (c['approach'] || c['ambient']) {
                        return [c['title'], c['publicName'], c['content'], c['toolName'], c['abilityName'], link, c['approach'], c['ambient']];

                    } else {
                        return [c['title'], c['publicName'], c['content'], c['toolName'], c['abilityName'], link];
                    }
                }),
                "pageLength": 25
            });

            document.getElementsByClassName('datatableCatalogs')[0].classList.remove('d-none');
        }).catch((err) => {
            error("Falha ao tentar carregar catalagos");
        });
}

function getCatalogFilters() {
    const filters = document.forms['filtersForm'];
    let dataFilters = {};

    const formElements = filters.querySelectorAll('input, select');

    formElements.forEach((element) => {
        const key = element.name || element.id;
        dataFilters[key] = element.value;
    });

    if (dataFilters['catalogType'] !== '2') {
        delete dataFilters['catalogApproach'];
        delete dataFilters['catalogEnvironment'];
    }

    return dataFilters;
}

function toggleFieldsVisibility(catalogType) {
    console.log(catalogType)
    switch (catalogType) {
        case '1':
        case '3': {
            document.getElementById('environmentFieldBox').classList.add('d-none');
            document.getElementById('approachFieldBox').classList.add('d-none');
            break;
        }
        case '2': {
            document.getElementById('environmentFieldBox').classList.remove('d-none');
            document.getElementById('approachFieldBox').classList.remove('d-none');
            break;
        }
    }
}

function resetFilters() {
    const filters = document.forms['filtersForm'];
    const formElements = filters.querySelectorAll('input, select');

    formElements.forEach((element) => {
        if (element.id !== "catalogType") {
            element.value = '';
        }
    });

    buildCatalogsDatatable();
}