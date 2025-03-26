
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('accepted-btn').addEventListener('click', function () {
            Swal.fire({
                title: "Êtes-vous sûr ?",
                text: "Cette action est irréversible !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Oui, Accepter !",
                cancelButtonText: "Annuler"
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('accepted-form');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch(form.action, {
                        method: "PUT",
                        body: new FormData(form),
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": csrfToken // Ajout du token CSRF
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.ok) {
                            Swal.fire("Accepté !", "Le recours a été accepté avec succès.", "success")
                            .then(() => {
                                // window.history.back(); 
                                window.location.reload(); // Recharge la page après confirmation

                            });
                        } else {
                            Swal.fire("Erreur !", data.message, "error");
                        }
                    })
                    .catch(error => {
                        Swal.fire("Erreur !", "Une erreur s'est produite.", "error");
                    });
                }
            });
        });
    });
