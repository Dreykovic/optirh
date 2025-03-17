
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('delete-btn').addEventListener('click', function () {
            Swal.fire({
                title: "Êtes-vous sûr ?",
                text: "Cette action est irréversible !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Oui, supprimer !",
                cancelButtonText: "Annuler"
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form');
                    fetch(form.action, {
                        method: "POST",
                        body: new FormData(form),
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.ok) {
                            Swal.fire("Supprimé !", "Le recours a été supprimé avec succès.", "success")
                            .then(() => {
                                // window.history.back(); 
                                window.location.href = document.referrer;

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
