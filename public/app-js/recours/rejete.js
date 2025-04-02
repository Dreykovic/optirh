
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('rejected-btn').addEventListener('click', function () {
        Swal.fire({
            title: "Êtes-vous sûr de l'irrecevabilité de ce recours ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Oui, Irrecevable !",
            cancelButtonText: "Annuler",
            html: `
                <div>
                    <label class='form-label'>Raisons</label>
                    <select class='form-control' id="rejection-reason" name='decision'>
                        <option value="" disabled selected>Choisissez une raison</option>
                        <option value="Incompétence">Incompétence</option>
                        <option value="Forclusion">Forclusion</option>
                        <option value="Défaut de Capacité">Défaut de Capacité</option>
                        <option value="Défaut de Qualité">Défaut de Qualité</option>
                        <option value="Défaut d'intérêt à agir">Défaut d'intérêt à agir</option>
                        <option value="Cas non prévu par la loi">Cas non prévu par la loi</option>
                        <option value="Absence de recours préalable">Absence de recours préalable</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
                <div id='other-reason-container' style='display:none;'>
                    <label class='form-label'>Autre raison</label>
                    <input class='form-control' id='other-reason' type='text' name='other_reason'>
                </div>
                <div>
                    <label class='form-label'>Décision N°:</label>
                    <input class='form-control' id='rejected-ref' name='rejected_ref' type="text">
                </div>
                <div>
                    <label class='form-label'>Fichier</label>
                    <input class='form-control' id='rejected-file' name='rejected_file' type="file">
                </div>
            `,
            preConfirm: () => {
                const selectedReason = document.getElementById('rejection-reason').value;
                const otherReason = document.getElementById('other-reason').value;
                const decisionRef = document.getElementById('rejected-ref').value;
                const decisionFile = document.getElementById('rejected-file').files[0];

                if (!selectedReason) {
                    Swal.showValidationMessage("Veuillez choisir une raison");
                    return false;
                }
                if (selectedReason === "Autre" && !otherReason.trim()) {
                    Swal.showValidationMessage("Veuillez spécifier la raison");
                    return false;
                }
                if (!decisionRef.trim()) {
                    Swal.showValidationMessage("Veuillez saisir un numéro de décision");
                    return false;
                }

                return {
                    decision: selectedReason === "Autre" ? otherReason : selectedReason,
                    decisionRef,
                    decisionFile
                };
            },
            didOpen: () => {
                document.getElementById('rejection-reason').addEventListener('change', function () {
                    document.getElementById('other-reason-container').style.display = this.value === "Autre" ? "block" : "none";
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('rejected-form');
                const formData = new FormData(form);
                formData.append('decision', result.value.decision);
                formData.append('rejected_ref', result.value.decisionRef);
                if (result.value.decisionFile) {
                    formData.append('rejected_file', result.value.decisionFile);
                }

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
                        Swal.fire("Rejeté !", "Recours irrecevable avec succès.", "success")
                        .then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire("Erreur !", data.message, "error");
                    }
                })
                .catch(() => {
                    Swal.fire("Erreur !", "Une erreur s'est produite.", "error");
                });
            }
        });
    });
});
