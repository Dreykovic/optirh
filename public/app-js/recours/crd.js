
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('crd-btn').addEventListener('click', function () {
        Swal.fire({
            title: "Êtes-vous sûr de cette décision ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Oui, Décider !",
            cancelButtonText: "Annuler",
            html: `
                <div>
                    <label class='form-label'>Raisons</label>
                    <select class='form-control' id="crd-reason" name='decision'>
                        <option value="" disabled selected>Choisissez une raison</option>
                        <option value="Fonde">Fonde</option>
                        <option value="Non Fonde">Non Fonde</option>
                        <option value="Desistement">Desistement</option>
                        <option value="Autre">Autre</option>
                        
                    </select>
                </div>
                <div id='other-reason-container' style='display:none;'>
                    <label class='form-label'>Autre raison</label>
                    <input class='form-control' id='other-reason' type='text' name='other_reason'>
                </div>
                <div>
                    <label class='form-label'>Décision N°:</label>
                    <input class='form-control' id='decided-ref' name='decided_ref' type="text">
                </div>
                <div>
                    <label class='form-label'>Fichier</label>
                    <input class='form-control' id='decided-file' name='decided_file' type="file">
                </div>
            `,
            preConfirm: () => {
                const selectedReason = document.getElementById('crd-reason').value;
                const otherReason = document.getElementById('other-reason').value;
                const decisionRef = document.getElementById('decided-ref').value;
                const decisionFile = document.getElementById('decided-file').files[0];

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
                document.getElementById('crd-reason').addEventListener('change', function () {
                    document.getElementById('other-reason-container').style.display = this.value === "Autre" ? "block" : "none";
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('crd-form');
                const formData = new FormData(form);
                formData.append('decision', result.value.decision);
                formData.append('decided_ref', result.value.decisionRef);
                if (result.value.decisionFile) {
                    formData.append('decided_file', result.value.decisionFile);
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
