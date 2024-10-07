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
let catalogsDatatableObj1 = null;
let catalogsDatatableObj2 = null;

async function buildCatalogsDatatable() {
    await locks['onload'];

    document.getElementById('loader').classList.remove('d-none');
    document.getElementById('searchDatatable1').classList.add('d-none');
    document.getElementById('searchDatatable2').classList.add('d-none');

    const filters = getCatalogFilters();

    fetch(`${apiUrl}/catalog/search.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 'filters': filters })
    })
        .then((resp) => resp.json())
        .then((resp) => {
            if (!resp['success']) {
                error(resp['msg']);
                return;
            }

            document.getElementById('loader').classList.add('d-none');


            if (catalogsDatatableObj1) {
                catalogsDatatableObj1.destroy();
                catalogsDatatableObj1 = null;
            }

            if (catalogsDatatableObj2) {
                catalogsDatatableObj2.destroy();
                catalogsDatatableObj2 = null;
            }

            let selectedTable;
            let tableData;

            if (filters.catalogType === '2') {
                selectedTable = 'searchDatatable2';
                tableData = resp['data'].map(function (c) {
                    const link = `<a class="text-link" href="${c['link']}" target="_blank">${c['link']}</a>`;
                    return [c['title'], c['publicName'], c['content'], c['toolName'], c['abilityName'], c['ambient'], c['approach'], link];
                });
            } else {
                selectedTable = 'searchDatatable1';
                tableData = resp['data'].map(function (c) {
                    const link = `<a class="text-link" href="${c['link']}" target="_blank">${c['link']}</a>`;
                    return [c['title'], c['publicName'], c['content'], c['toolName'], c['abilityName'], link];
                });
            }

            document.getElementById(selectedTable).classList.remove('d-none');

            if (filters.catalogType === '2') {
                catalogsDatatableObj2 = $('#catalogTableType2').DataTable({
                    data: tableData,
                    columns: [
                        { title: "Título" },
                        { title: "Público Alvo" },
                        { title: "Conteúdo" },
                        { title: "Ferramenta" },
                        { title: "Habilidade" },
                        { title: "Ambiente" },
                        { title: "Abordagem" },
                        { title: "Link" }
                    ],
                    pageLength: 25
                });
            } else {
                catalogsDatatableObj1 = $('#catalogTableType1').DataTable({
                    data: tableData,
                    columns: [
                        { title: "Título" },
                        { title: "Nome Público" },
                        { title: "Conteúdo" },
                        { title: "Ferramenta" },
                        { title: "Habilidade" },
                        { title: "Link" }
                    ],
                    pageLength: 25
                });
            }
        })
        .catch((err) => {
            error(err);
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