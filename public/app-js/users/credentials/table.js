"use strict";

let AppUserCredentialsManager = (function () {

    // Private function to update bulk UI
    function updateBulkUI() {
        const count = $('.user-checkbox:checked').length;
        const total = $('.user-checkbox').length;

        // Update select all checkbox state
        if (count === 0) {
            $('#selectAll').prop('checked', false).prop('indeterminate', false);
        } else if (count === total) {
            $('#selectAll').prop('checked', true).prop('indeterminate', false);
        } else {
            $('#selectAll').prop('checked', false).prop('indeterminate', true);
        }

        // Show/hide bulk actions
        if (count > 0) {
            $('.bulk-actions').removeClass('d-none');
            $('.selected-count').removeClass('d-none').text(count + ' sélectionné(s)');
        } else {
            $('.bulk-actions').addClass('d-none');
            $('.selected-count').addClass('d-none');
        }
    }

    // Private function for bulk status update
    function bulkUpdateStatus(status) {
        const userIds = $('.user-checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        if (userIds.length === 0) return;

        const action = status === 'ACTIVATED' ? 'activer' : 'désactiver';
        const message = 'Voulez-vous ' + action + ' <strong>' + userIds.length + '</strong> utilisateur(s) ?';

        Swal.fire({
            title: 'Confirmation',
            html: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui, ' + action,
            cancelButtonText: 'Annuler',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-primary me-2',
                cancelButton: 'btn btn-secondary'
            }
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/opti-hr/users-management/credentials/bulk-status',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        user_ids: userIds,
                        status: status
                    },
                    success: function (response) {
                        if (response.ok) {
                            AppModules.showToast(response.message, 'success');
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        } else {
                            AppModules.showConfirmAlert(response.message, 'error');
                        }
                    },
                    error: function (xhr) {
                        AppModules.showConfirmAlert('Une erreur est survenue.', 'error');
                    }
                });
            }
        });
    }

    return {
        init: function () {
            AppModules.initDataTable("#usersTable", {
                columnDefs: [
                    { orderable: false, targets: 0 }
                ]
            });
            this.initBulkActions();
            this.initResendCredentials();
        },

        initBulkActions: function () {
            // Select All checkbox
            $('#selectAll').on('change', function () {
                $('.user-checkbox').prop('checked', $(this).is(':checked'));
                updateBulkUI();
            });

            // Individual checkbox
            $(document).on('change', '.user-checkbox', function () {
                updateBulkUI();
            });

            // Bulk Activate
            $('#bulkActivate').on('click', function () {
                bulkUpdateStatus('ACTIVATED');
            });

            // Bulk Deactivate
            $('#bulkDeactivate').on('click', function () {
                bulkUpdateStatus('DEACTIVATED');
            });
        },

        initResendCredentials: function () {
            $(document).on('click', '.resendCredentialsBtn', function (e) {
                e.preventDefault();
                const url = $(this).data('url');
                const $btn = $(this);

                Swal.fire({
                    title: 'Renvoyer les identifiants',
                    html: '<p>Un <strong>nouveau mot de passe</strong> sera généré et envoyé par email à l\'utilisateur.</p><p class="text-warning"><i class="icofont-warning"></i> L\'ancien mot de passe ne sera plus valide.</p>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="icofont-email"></i> Renvoyer',
                    cancelButtonText: 'Annuler',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-info me-2',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Envoi en cours...',
                            html: 'Veuillez patienter',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: function () {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                if (response.ok) {
                                    Swal.fire({
                                        title: 'Succès !',
                                        html: '<p>Les identifiants ont été renvoyés avec succès.</p><p class="text-muted small">Un email a été envoyé à l\'utilisateur.</p>',
                                        icon: 'success',
                                        confirmButtonText: 'OK',
                                        buttonsStyling: false,
                                        customClass: {
                                            confirmButton: 'btn btn-primary'
                                        }
                                    }).then(function () {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Erreur',
                                        text: response.message || 'Une erreur est survenue.',
                                        icon: 'error',
                                        confirmButtonText: 'OK',
                                        buttonsStyling: false,
                                        customClass: {
                                            confirmButton: 'btn btn-primary'
                                        }
                                    });
                                }
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    title: 'Erreur',
                                    text: 'Une erreur est survenue lors de l\'envoi.',
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                    buttonsStyling: false,
                                    customClass: {
                                        confirmButton: 'btn btn-primary'
                                    }
                                });
                            }
                        });
                    }
                });
            });
        }
    };
})();

document.addEventListener("DOMContentLoaded", function (e) {
    AppUserCredentialsManager.init();
});
