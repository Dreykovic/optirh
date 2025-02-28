

const paginator = new Paginator({
    apiUrl: '/recours/api/data', 
    renderElement: document.getElementById('recours'),
    searchInput: document.getElementById('searchInput'), // Input de recherche
    department: document.getElementById('directorInput'),
    limitSelect: document.getElementById('limitSelect'), // Sélecteur de limite
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
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="">Détails</a></li>
                                    <!-- <li><button class="dropdown-item text-danger">Supprimer</button></li>-->
                                </ul>
                            </div>
                        </td>
                    `;

            tableBody.appendChild(row);
        });
    }
}
});

