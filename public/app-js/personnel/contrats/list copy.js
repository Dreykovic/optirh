

const paginator = new Paginator({
    apiUrl: '/contrats/request/ON_GOING', 
    renderElement: document.getElementById('contrats'),
    searchInput: document.getElementById('searchInput'), // Input de recherche
    department: document.getElementById('directorInput'),
    limitSelect: document.getElementById('limitSelect'), // Sélecteur de limite
    paginationElement: document.getElementById('pagination'), // Élément pour la pagination
renderCallback: (contrats) => {
    const tableBody = document.querySelector('#contrats tbody');
    tableBody.innerHTML = '';
    if (contrats.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Aucun contrat trouvé.</td></tr>';
    } else {
        contrats.forEach(contrat => {
            const row = document.createElement('tr');
        
            row.innerHTML = `
                        <td>
                            <div class="d-flex align-items-center">
                            <i class="icofont icofont-${contrat.employee.gender === 'FEMALE' ? 'businesswoman' : 'business-man-alt-2'} fs-3 avatar rounded-circle"></i>
                                <span class='text-uppercase mx-2'>${contrat.last_name}</span> <span class='text-capitalize'>${contrat.first_name}</span>
                            </div>
                        </td>
                        <td>${contrat.job.department}</td>
                        <td>${contrat.job}</td>
                        <td>${contrat.begin_date}</td>
                        <td>${contrat.type}</td>
                        <td>${contrat.absence_balance}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/membres/${contrat.id}">Détails</a></li>
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

