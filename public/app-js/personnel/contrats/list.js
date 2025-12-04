// Fonction pour initialiser le paginator avec un statut spécifique
function initPaginator(status) {
    window.currentStatus = status; // Stocker le statut actuel
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
                        <td>
                            ${status === 'ON_GOING' && document.getElementById('editBalanceModal') ?
                                `<span class="badge bg-primary fs-6 edit-balance-btn"
                                    style="cursor: pointer;"
                                    data-duty-id="${contrat.duty_id}"
                                    data-employee-name="${contrat.last_name} ${contrat.first_name}"
                                    data-balance="${contrat.absence_balance}"
                                    title="Cliquez pour modifier">
                                    ${contrat.absence_balance} <i class="icofont-edit ms-1"></i>
                                </span>`
                                : `<span class="badge bg-secondary fs-6">${contrat.absence_balance}</span>`
                            }
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                                <ul class="dropdown-menu">
                                    ${status == 'ON_GOING' && document.getElementById('editBalanceModal') ?
                                        `<li>
                                            <a class="dropdown-item edit-balance-link"
                                                href="#"
                                                data-duty-id="${contrat.duty_id}"
                                                data-employee-name="${contrat.last_name} ${contrat.first_name}"
                                                data-balance="${contrat.absence_balance}">
                                                <i class="icofont-ui-edit me-2"></i>Modifier solde congés
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>`
                                    : ''}
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

                // Ajouter les event listeners pour les boutons d'édition de solde
                attachEditBalanceListeners();
            }
        }
    });
}

// Fonction pour ouvrir le modal d'édition du solde
function openEditBalanceModal(dutyId, employeeName, currentBalance) {
    document.getElementById('edit_duty_id').value = dutyId;
    document.getElementById('edit_employee_name').textContent = employeeName;
    document.getElementById('edit_current_balance').textContent = currentBalance + ' jour(s)';
    document.getElementById('edit_absence_balance').value = currentBalance;
    document.getElementById('edit_reason').value = '';

    const modal = new bootstrap.Modal(document.getElementById('editBalanceModal'));
    modal.show();
}

// Fonction pour sauvegarder le solde
function saveAbsenceBalance() {
    const dutyId = document.getElementById('edit_duty_id').value;
    const balance = document.getElementById('edit_absence_balance').value;
    const reason = document.getElementById('edit_reason').value;

    if (!balance || balance < 0 || balance > 365) {
        showToast('danger', 'Veuillez entrer un solde valide (0-365 jours)');
        return;
    }

    const saveBtn = document.querySelector('#editBalanceModal .btn-primary');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Enregistrement...';
    saveBtn.disabled = true;

    fetch(`/opti-hr/contrats/${dutyId}/absence-balance`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            absence_balance: parseInt(balance),
            reason: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;

        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('editBalanceModal')).hide();
            showToast('success', data.message);
            // Rafraîchir la liste
            if (window.paginator) {
                window.paginator.fetchData();
            }
        } else {
            showToast('danger', data.message || 'Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
        console.error('Error:', error);
        showToast('danger', 'Erreur lors de la mise à jour du solde');
    });
}

// Attacher les event listeners pour les éléments d'édition de solde
function attachEditBalanceListeners() {
    // Pour les badges cliquables
    document.querySelectorAll('.edit-balance-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            openEditBalanceModal(
                this.dataset.dutyId,
                this.dataset.employeeName,
                this.dataset.balance
            );
        });
    });

    // Pour les liens dans le dropdown
    document.querySelectorAll('.edit-balance-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            openEditBalanceModal(
                this.dataset.dutyId,
                this.dataset.employeeName,
                this.dataset.balance
            );
        });
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
