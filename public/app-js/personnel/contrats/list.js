// Fonction pour initialiser le paginator avec un statut spécifique
function initPaginator(status) {
    window.paginator = new Paginator({
        apiUrl: `/opti-hr/contrats/request/${status}`, // URL dynamique selon le statut
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
                                    ${status == 'ON_GOING' ? 
                                        `<li>
                                            <a class="dropdown-item action-btn" 
                                                href="#" 
                                                data-id="${contrat.duty_id}" 
                                                data-url="/opti-hr/contrats/${contrat.duty_id}/suspended"
                                                data-action="Suspendre" 
                                                data-message="Cette action suspendra cet employé.">
                                                Suspendre
                                            </a>
                                        </li>` 
                                    : ''}
                                    ${status == 'ON_GOING' || status == 'SUSPENDED' ? 
                                        `<li>
                                            <a class="dropdown-item action-btn" 
                                                href="#" 
                                                data-id="${contrat.duty_id}" 
                                                data-url="/opti-hr/contrats/${contrat.duty_id}/resigned" 
                                                data-action="Démissioner" 
                                                data-message="Cette action va marquer cet employé comme Démissionaire.">
                                                Démissioner
                                            </a>
                                        </li>` 
                                    : ''}
                                    ${status == 'ON_GOING' || status == 'SUSPENDED' ? 
                                        `<li>
                                            <a class="dropdown-item action-btn" 
                                                href="#" 
                                                data-id="${contrat.duty_id}" 
                                                data-url="/opti-hr/contrats/${contrat.duty_id}/dismissed" 
                                                data-action="Licencier" 
                                                data-message="Cette action va marquer cet employé comme licencié.">
                                                Licencier
                                            </a>
                                        </li>` 
                                    : ''}
                                     ${status == 'ON_GOING' ? 
                                        `<li>
                                            <a class="dropdown-item action-btn" 
                                                href="#" 
                                                data-id="${contrat.duty_id}" 
                                                data-url="/opti-hr/contrats/${contrat.duty_id}/ended" 
                                                data-action=" Mettre fin au contrat" 
                                                data-message="Cette action va mettre fin au contrat de cet employé.">
                                                Terminer
                                            </a>
                                        </li>` 
                                    : ''}
                                    
                                    ${status !== 'ON_GOING' && status !== 'DELETED'? 
                                        `<li>
                                            <a class="dropdown-item action-btn" 
                                                href="#" 
                                                data-id="${contrat.duty_id}" 
                                                data-url="/opti-hr/contrats/${contrat.duty_id}/deleted" 
                                                data-action="Supprimer" 
                                                data-message="Cette action Supprimera ce contrat.">
                                                Supprimer
                                            </a>
                                        </li>` 
                                    : ''}
                                    ${status !== 'ON_GOING' && status !== 'DELETED' ?
                                         `<li>
                                            <a class="dropdown-item action-btn" 
                                                href="#" 
                                                data-id="${contrat.duty_id}" 
                                                data-url="/opti-hr/contrats/${contrat.duty_id}/ongoing" 
                                                data-action="Réintégrer" 
                                                data-message="Cette action Réintégrera cet employé.">
                                                Réintégrer
                                            </a>
                                        </li>`  
                                    : ''}
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
