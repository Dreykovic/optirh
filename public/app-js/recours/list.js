

const paginator = new Paginator({
    apiUrl: '/recours/api/data', 
    renderElement: document.getElementById('recours'),
    searchInput: document.getElementById('searchInput'), // Input de recherche
    department: document.getElementById('directorInput'),
    limitSelect: document.getElementById('limitSelect'), // Sélecteur de limite
    startDate: document.getElementById('startDate'), // Ajout du champ de début
    endDate: document.getElementById('endDate'), // Ajout du champ de fin
    filterContainer : document.getElementById('filterContainer'),
    paginationElement: document.getElementById('pagination'), // Élément pour la pagination

renderCallback: (recours) => {
    const tableBody = document.querySelector('#recours tbody');
    tableBody.innerHTML = '';
    if (recours.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Aucun recours trouvé.</td></tr>';
    } else {
        recours.forEach(appeal => {
            const row = document.createElement('tr');
        
            row.innerHTML = `
                        <td>
                            <div class="d-flex align-items-center">
                                <span class='text-uppercase mx-2'>${appeal.reference}</span>
                            </div>
                        </td>
                        <td>${appeal.applicant}</td>
                        <td>${appeal.object}</td>
                        <td>${appeal.deposit_date} À ${appeal.deposit_hour}</td>
                        <td>${appeal.analyse_status}</td>
                        <td>${appeal.decision ?? '-'}</td>
                        <td>${appeal.day_count ?? '-'} </td>
                        <td><a href="/recours/show/${appeal.id}">
                            <i class="icofont-long-arrow-right fs-4"></i>
                            </a>
                        </td>
                       
                    `;

            tableBody.appendChild(row);
        });
    }
}
});

