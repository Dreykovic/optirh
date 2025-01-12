// Fonction pour initialiser le paginator avec un statut spécifique
function initPaginator(status) {
    window.paginator = new Paginator({
        apiUrl: `/contrats/request/${status}`, // URL dynamique selon le statut
        renderElement: document.getElementById('contrats'),
        searchInput: document.getElementById('searchInput'), // Input de recherche
        department: document.getElementById('directorInput'), // Sélecteur de département
        limitSelect: document.getElementById('limitSelect'), // Sélecteur de limite
        paginationElement: document.getElementById('pagination'), // Élément pour la pagination
        renderCallback: (contrats) => {
            const tableBody = document.querySelector('#contrats tbody');
            tableBody.innerHTML = '';
            if (contrats.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Aucun contrat trouvé.</td></tr>';
            } else {
                contrats.forEach(contrat => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="icofont icofont-${contrat.gender === 'FEMALE' ? 'businesswoman' : 'business-man-alt-2'} fs-3 avatar rounded-circle"></i>
                                <span class='text-uppercase mx-2'>${contrat.last_name}</span> 
                                <span class='text-capitalize'>${contrat.first_name}</span>
                            </div>
                        </td>
                        <td>${contrat.department_name}</td>
                        <td>${contrat.job_title}</td>
                        <td>${contrat.begin_date}</td>
                        <td>${contrat.type}</td>
                        <td>${contrat.absence_balance}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                                <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item action-btn" 
                                        href="#" 
                                        data-id="${contrat.duty_id}" 
                                        data-url="/contrats/${contrat.duty_id}/suspended" 
                                        data-action="Suspendre" 
                                        data-message="Cette action suspendra cet employé.">
                                        Suspendre
                                    </a>
                                    </li>
                                    <li><a class="dropdown-item" href="">Démissioner</a></li>
                                    <li><a class="dropdown-item" href="">Licencier</a></li>
                                    <li><a class="dropdown-item" href="">Terminer</a></li>
                                    <li><a class="dropdown-item" href="">Supprimer</a></li>
                                    <li><a class="dropdown-item" href="">${status !== 'ON_GOING'? 'Réintégrer':''}</a></li>
                                </ul>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            }
        }
    });
}

// Initialisation après chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
    // Écouteurs d'événements pour les onglets
    document.querySelectorAll('.nav-tabs .nav-link').forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.nav-tabs .nav-link').forEach(link => link.classList.remove('active'));
            tab.classList.add('active');
            const status = tab.getAttribute('href').replace('#', '').toUpperCase();
            initPaginator(status);
        });
    });

    // Initialiser avec le statut par défaut (En cours)
    initPaginator('ON_GOING');
});
