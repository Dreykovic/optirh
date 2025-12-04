"use strict";

/**
 * Gestionnaire d'approbation des absences avec gestion du solde insuffisant
 */
let AbsenceApprovalManager = (function () {
    let pendingAbsenceId = null;
    let pendingApproveUrl = null;
    let insufficientBalanceModal = null;

    /**
     * Initialise le modal de solde insuffisant
     */
    const initModal = () => {
        const modalElement = document.getElementById('insufficientBalanceModal');
        if (modalElement) {
            insufficientBalanceModal = new bootstrap.Modal(modalElement);

            // Handler pour les boutons d'option
            const optionButtons = modalElement.querySelectorAll('.insufficient-option-btn');
            optionButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    handleOptionSelection(this.dataset.option);
                });
            });
        }
    };

    /**
     * Affiche le modal avec les informations de solde
     */
    const showInsufficientBalanceModal = (data) => {
        if (!insufficientBalanceModal) {
            console.error('Modal de solde insuffisant non trouvé');
            AppModules.showConfirmAlert(
                'Le solde de congés est insuffisant. Veuillez rafraîchir la page.',
                'warning'
            );
            return;
        }

        // Remplir les informations
        document.getElementById('insufficientEmployeeName').textContent = data.employee_name || 'L\'employé';
        document.getElementById('insufficientCurrentBalance').textContent = data.current_balance;
        document.getElementById('insufficientRequestedDays').textContent = data.requested_days;
        document.getElementById('insufficientAvailableDays').textContent = data.current_balance;

        // Stocker l'ID pour l'action suivante
        pendingAbsenceId = data.absence_id;

        // Afficher le modal
        insufficientBalanceModal.show();
    };

    /**
     * Gère la sélection d'une option
     */
    const handleOptionSelection = (option) => {
        if (!pendingAbsenceId) {
            console.error('Aucune absence en attente');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const url = `/opti-hr/attendances/absences/request/approve-with-option/${pendingAbsenceId}`;

        // Désactiver les boutons pendant le traitement
        const optionButtons = document.querySelectorAll('.insufficient-option-btn');
        optionButtons.forEach(btn => {
            btn.disabled = true;
            if (btn.dataset.option === option) {
                btn.innerHTML = `
                    <div class="d-flex align-items-center justify-content-center">
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                        Traitement en cours...
                    </div>
                `;
            }
        });

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ option: option })
        })
            .then(response => response.json())
            .then(data => {
                // Fermer le modal
                insufficientBalanceModal.hide();

                if (data.ok) {
                    AppModules.showConfirmAlert(data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    AppModules.showConfirmAlert(data.message || 'Une erreur est survenue', 'error');
                    resetOptionButtons();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                insufficientBalanceModal.hide();
                AppModules.showConfirmAlert('Une erreur est survenue lors du traitement', 'error');
                resetOptionButtons();
            });
    };

    /**
     * Réinitialise les boutons d'option
     */
    const resetOptionButtons = () => {
        const modalElement = document.getElementById('insufficientBalanceModal');
        if (!modalElement) return;

        const buttons = modalElement.querySelectorAll('.insufficient-option-btn');
        buttons.forEach(btn => {
            btn.disabled = false;
            const option = btn.dataset.option;

            if (option === 'make_non_deductible') {
                btn.innerHTML = `
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="icofont-check-circled fs-3 text-success"></i>
                        </div>
                        <div>
                            <strong class="d-block">Passer en non-déductible</strong>
                            <small class="text-muted">
                                L'absence sera approuvée sans impact sur le solde de congés.
                                Le solde restera intact.
                            </small>
                        </div>
                    </div>
                `;
            } else if (option === 'deduct_available') {
                const availableDays = document.getElementById('insufficientAvailableDays').textContent;
                btn.innerHTML = `
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="icofont-minus-circle fs-3 text-primary"></i>
                        </div>
                        <div>
                            <strong class="d-block">Déduire le solde disponible uniquement</strong>
                            <small class="text-muted">
                                Seuls <span class="fw-bold">${availableDays}</span> jour(s) seront déduits.
                                Le solde passera à 0.
                            </small>
                        </div>
                    </div>
                `;
            }
        });
    };

    /**
     * Override le comportement par défaut des boutons d'approbation d'absence
     */
    const overrideApprovalButtons = () => {
        // Sélectionner tous les formulaires d'approbation d'absence
        const approveContainers = document.querySelectorAll('[id^="absenceApproveForm"]');

        approveContainers.forEach(container => {
            const btn = container.querySelector('.modelUpdateBtn');
            const form = container.querySelector('form');

            if (!btn || !form) return;

            const url = form.getAttribute('data-model-update-url');

            // Supprimer l'ancien event listener en clonant le bouton
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);

            // Ajouter notre propre handler
            newBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const formData = new FormData(form);
                handleApprovalSubmission(newBtn, formData, url);
            });
        });
    };

    /**
     * Gère la soumission d'approbation avec détection du solde insuffisant
     */
    const handleApprovalSubmission = (btn, formData, url) => {
        AppModules.showSpinner(btn);

        axios.post(url, formData)
            .then(response => {
                AppModules.hideSpinner(btn);
                const { data } = response;
                const status = data.ok ? 'success' : 'error';

                AppModules.showConfirmAlert(data.message, status).then(result => {
                    if (result.isDismissed || result.isConfirmed) {
                        if (data.ok) {
                            location.reload();
                        }
                    }
                });
            })
            .catch(error => {
                AppModules.hideSpinner(btn);
                console.log('Erreur catch:', error);

                // Vérifier si c'est une erreur de solde insuffisant
                if (error.response &&
                    error.response.status === 422 &&
                    error.response.data &&
                    error.response.data.insufficient_balance) {

                    showInsufficientBalanceModal(error.response.data);
                } else {
                    // Erreur standard
                    let errorMessage = 'Une erreur est survenue';

                    if (error.response && error.response.data) {
                        if (error.response.data.message) {
                            errorMessage = error.response.data.message;

                            if (error.response.data.errors) {
                                errorMessage += ':<br>';
                                errorMessage += Object.values(error.response.data.errors)
                                    .flat()
                                    .map(msg => `• ${msg}`)
                                    .join('<br>');
                            }
                        }
                    }

                    AppModules.showConfirmAlert(errorMessage, 'error');
                }
            });
    };

    return {
        init: () => {
            console.log('Initialisation AbsenceApprovalManager');
            initModal();
            overrideApprovalButtons();
        }
    };
})();

// Initialisation après le chargement du DOM et après AppAdminModelUpdateManager
document.addEventListener('DOMContentLoaded', () => {
    // Attendre un court instant pour s'assurer que les autres scripts sont initialisés
    setTimeout(() => {
        AbsenceApprovalManager.init();
    }, 100);
});
