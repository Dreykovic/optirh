

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
        tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Aucun recours trouvé.</td></tr>';
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
            <td>${appeal.deposit_date} </td>
            <td>${appeal.deposit_hour} </td>
            <td class="${appeal.analyse_status=='RECEVABLE'? 'text-success':(appeal.analyse_status=='IRRECEVABLE'?'text-danger':'text-warning')} fw-bold" >
                ${appeal.analyse_status}
            </td>
            <td class='text-info fw-bold'>${appeal.decided ?? appeal.suspended ?? 'N/A'}</td>
            
            <td>
                <a href="/recours/show/${appeal.id}">
                    <i class="icofont-long-arrow-right fs-4"></i>
                </a>
            </td>
        `;
        

            tableBody.appendChild(row);
        });
    }
}
});

