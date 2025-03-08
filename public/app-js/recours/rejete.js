    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('rejected-btn').addEventListener('click', function () {
            Swal.fire({
                title: "Êtes-vous sûr de rejeter ce recours ?",
                text: "Veuillez préciser la raison du rejet.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Oui, rejeter !",
                cancelButtonText: "Annuler",
                html: `
                    <select id="rejection-reason" class="swal2-select form-control w-75" name='decision'>
                        <option value="" disabled selected>Choisissez une raison</option>
                        <option value="HORS COMPETENCE">Hors Compétence</option>
                        <option value="IRRECEVABLE">Irrécevabilité</option>
                        <option value="FORCLUSION">Forclusion</option>
                    </select>
                `,
                preConfirm: () => {
                    const selectedReason = document.getElementById('rejection-reason').value;
                    
                    if (!selectedReason) {
                        Swal.showValidationMessage("Veuillez choisir une raison");
                        return false;
                    }

                    return selectedReason === "Autre" && otherReason ? otherReason : selectedReason;
                },
                didOpen: () => {
                    const rejectionSelect = document.getElementById('rejection-reason');
                    const otherInput = document.getElementById('other-reason');
                    
                    rejectionSelect.addEventListener('change', function () {
                        if (this.value === "Autre") {
                            otherInput.style.display = "block";
                        } else {
                            otherInput.style.display = "none";
                        }
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('rejected-form');
                    const formData = new FormData(form);
                    formData.append('decision', result.value); // Ajouter la raison du rejet

                    fetch(form.action, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.ok) {
                            Swal.fire("Rejeté !", "Le recours a été rejeté avec succès.", "success")
                            .then(() => {
                                window.location.reload();
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
