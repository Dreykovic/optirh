"use strict";

/**
 * Annual Decisions Page Manager
 * Handles dynamic modal for create/edit operations and DataTable initialization
 */
let AppDecisionsManager = (function () {
    // Store decisions data from JSON
    let decisionsData = {};

    // DOM elements
    const elements = {
        modal: null,
        form: null,
        decisionId: null,
        modalTitle: null,
        modalIcon: null,
        submitBtnText: null,
        pdfHelpText: null,
        numberInput: null,
        yearInput: null,
        referenceInput: null,
        dateInput: null,
        pdfInput: null
    };

    /**
     * Initialize DOM elements
     */
    function initElements() {
        elements.modal = document.getElementById('decisionModal');
        elements.form = document.getElementById('modelAddForm');
        elements.decisionId = document.getElementById('decisionId');
        elements.modalTitle = document.getElementById('modalTitleText');
        elements.modalIcon = document.getElementById('modalIcon');
        elements.submitBtnText = document.getElementById('submitBtnText');
        elements.pdfHelpText = document.getElementById('pdfHelpText');
        elements.numberInput = document.getElementById('decisionNumber');
        elements.yearInput = document.getElementById('decisionYear');
        elements.referenceInput = document.getElementById('decisionReference');
        elements.dateInput = document.getElementById('decisionDate');
        elements.pdfInput = document.getElementById('decisionPdf');
    }

    /**
     * Load decisions data from embedded JSON
     */
    function loadDecisionsData() {
        const dataElement = document.getElementById('decisionsData');
        if (dataElement) {
            try {
                decisionsData = JSON.parse(dataElement.textContent);
            } catch (e) {
                console.error('Error parsing decisions data:', e);
                decisionsData = {};
            }
        }
    }

    /**
     * Reset modal form to default state (create mode)
     */
    function resetModal() {
        // Reset form
        if (elements.form) {
            elements.form.reset();
        }

        // Clear decision ID
        if (elements.decisionId) {
            elements.decisionId.value = '';
        }

        // Update form URL to create endpoint
        if (elements.form) {
            const baseUrl = elements.form.dataset.modelAddUrl;
            elements.form.dataset.modelAddUrl = baseUrl.replace(/\/\d+$/, '');
        }

        // Update modal title and icon
        if (elements.modalTitle) {
            elements.modalTitle.textContent = 'Nouvelle decision';
        }
        if (elements.modalIcon) {
            elements.modalIcon.className = 'icofont-plus-circle me-2';
        }
        if (elements.submitBtnText) {
            elements.submitBtnText.textContent = 'Enregistrer';
        }

        // Reset PDF help text
        if (elements.pdfHelpText) {
            elements.pdfHelpText.textContent = 'Formats acceptes: PDF (max. 10 Mo)';
        }

        // Set default values
        if (elements.yearInput) {
            elements.yearInput.value = new Date().getFullYear();
        }
        if (elements.dateInput) {
            elements.dateInput.value = new Date().toISOString().split('T')[0];
        }
    }

    /**
     * Fill modal with decision data (edit mode)
     * @param {number} decisionId - The ID of the decision to edit
     */
    function fillModal(decisionId) {
        const decision = decisionsData[decisionId];

        if (!decision) {
            console.error('Decision not found:', decisionId);
            AppModules.showToastError('Decision introuvable');
            return;
        }

        // Set decision ID
        if (elements.decisionId) {
            elements.decisionId.value = decisionId;
        }

        // Update form URL to include decision ID
        if (elements.form) {
            const baseUrl = elements.form.dataset.modelAddUrl.replace(/\/\d*$/, '');
            elements.form.dataset.modelAddUrl = `${baseUrl}/${decisionId}`;
        }

        // Fill form fields
        if (elements.numberInput) {
            elements.numberInput.value = decision.number || '';
        }
        if (elements.yearInput) {
            elements.yearInput.value = decision.year || new Date().getFullYear();
        }
        if (elements.referenceInput) {
            elements.referenceInput.value = decision.reference || '';
        }
        if (elements.dateInput) {
            elements.dateInput.value = decision.date || '';
        }

        // Update modal title and icon
        if (elements.modalTitle) {
            elements.modalTitle.textContent = 'Modifier la decision';
        }
        if (elements.modalIcon) {
            elements.modalIcon.className = 'icofont-edit me-2';
        }
        if (elements.submitBtnText) {
            elements.submitBtnText.textContent = 'Mettre a jour';
        }

        // Update PDF help text if file exists
        if (elements.pdfHelpText && decision.pdf) {
            elements.pdfHelpText.innerHTML = '<i class="icofont-info-circle me-1"></i>Un document est deja associe. Choisir un nouveau fichier remplacera l\'ancien.';
        }
    }

    /**
     * Handle modal hidden event - reset form
     */
    function onModalHidden() {
        resetModal();
    }

    /**
     * Initialize event listeners
     */
    function initEventListeners() {
        // Modal hidden event - reset form
        if (elements.modal) {
            elements.modal.addEventListener('hidden.bs.modal', onModalHidden);
        }
    }

    /**
     * Initialize DataTable
     */
    function initDataTable() {
        if (typeof AppModules !== 'undefined' && AppModules.initDataTable) {
            AppModules.initDataTable("#decisionsTable");
        }
    }

    return {
        /**
         * Initialize the page manager
         */
        init: function() {
            initElements();
            loadDecisionsData();
            initEventListeners();
            initDataTable();
        },

        /**
         * Open modal for creating or editing a decision
         * @param {number|null} decisionId - The ID of the decision to edit, or null for create
         */
        openModal: function(decisionId = null) {
            if (decisionId) {
                fillModal(decisionId);
            } else {
                resetModal();
            }

            // Show modal
            if (elements.modal) {
                const modal = bootstrap.Modal.getOrCreateInstance(elements.modal);
                modal.show();
            }
        }
    };
})();

/**
 * Global function for opening decision modal (used in onclick handlers)
 * @param {number|null} decisionId - The ID of the decision to edit, or null for create
 */
function openDecisionModal(decisionId = null) {
    AppDecisionsManager.openModal(decisionId);
}

// Initialize on DOM ready
document.addEventListener("DOMContentLoaded", function() {
    AppDecisionsManager.init();
});
