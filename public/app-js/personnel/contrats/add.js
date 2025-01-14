document.addEventListener('DOMContentLoaded', () => {
    // Sélectionner un parent existant (par exemple, le tableau contenant les contrats)
    const tableBody = document.querySelector('#contrats tbody');

    // Délégation d'événements pour les boutons avec la classe .action-btn
    tableBody.addEventListener('click', (event) => {
        const button = event.target.closest('.action-btn'); // Vérifier si l'élément cliqué est un bouton d'action
        if (button) {
            event.preventDefault(); // Empêcher la navigation par défaut
            
            const action = button.getAttribute('data-action'); // Nom de l'action (ex: Suspendre)
            const url = button.getAttribute('data-url'); // URL pour la requête
            const message = button.getAttribute('data-message'); // Message de confirmation
            const dutyId = button.getAttribute('data-id'); // ID de l'entité (contrat ou autre)

            Swal.fire({
                title: `Êtes-vous sûr de vouloir ${action.toLowerCase()} ?`,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Oui, ${action.toLowerCase()}`,
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envoyer la requête AJAX
                    fetch(url, {
                        method: 'PUT', // Changez le type de requête si nécessaire
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.ok) {
                            Swal.fire(
                                `${action} réussi !`,
                                data.message || `L'action ${action.toLowerCase()} a été réalisée avec succès.`,
                                'success'
                            );
                            // Optionnel : Mettre à jour l'interface utilisateur
                            button.closest('tr').remove(); // Supprimer la ligne du tableau
                        } else {
                            Swal.fire(
                                'Erreur',
                                data.message || `Une erreur est survenue lors de l'action ${action.toLowerCase()}.`,
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Erreur',
                            'Une erreur inattendue est survenue.',
                            'error'
                        );
                    });
                }
            });
        }
    });
});
