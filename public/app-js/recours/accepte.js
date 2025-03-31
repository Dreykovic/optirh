
// document.addEventListener('DOMContentLoaded', function () {
//     document.getElementById('accepted-btn').addEventListener('click', function () {
//         Swal.fire({
//             title: "Êtes-vous sûr ?",
//             text: "Cette action est irréversible !",
//             icon: "warning",
//             showCancelButton: true,
//             confirmButtonColor: "#d33",
//             cancelButtonColor: "#3085d6",
//             confirmButtonText: "Oui, Accepter !",
//             cancelButtonText: "Annuler"
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 const form = document.getElementById('accepted-form');
//                 const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
//                 fetch(form.action, {
//                     method: "PUT",
//                     body: new FormData(form),
//                     headers: {
//                         "X-Requested-With": "XMLHttpRequest",
//                         "X-CSRF-TOKEN": csrfToken // Ajout du token CSRF
//                     }
//                 })
//                 .then(response => response.json())
//                 .then(data => {
//                     if (data.ok) {
//                         Swal.fire("Accepté !", "Le recours a été accepté avec succès.", "success")
//                         .then(() => {
//                             // window.history.back(); 
//                             window.location.reload(); // Recharge la page après confirmation

//                         });
//                     } else {
//                         Swal.fire("Erreur !", data.message, "error");
//                     }
//                 })
//                 .catch(error => {
//                     Swal.fire("Erreur !", "Une erreur s'est produite.", "error");
//                 });
//             }
//         });
//     });
// });


document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('accepted-btn').addEventListener('click', function () {
        Swal.fire({
            title: "Êtes-vous sûr de la recevabilité du recours ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Oui, Accepter !",
            cancelButtonText: "Annuler",
            html: `
                <div>
                    <label class='form-label'>Décision N°:</label>
                    <input class='form-control' id='decision-ref' name='decision_ref' type="text">
                </div>
                <div>
                    <label class='form-label'>Fichier</label>
                    <input class='form-control' id='decision-file' name='decision_file' type="file">
                </div>
            `,
            preConfirm: () => {
                const decisionRef = document.getElementById('decision-ref').value;
                const decisionFile = document.getElementById('decision-file').files[0];
                
                if (!decisionRef) {
                    Swal.showValidationMessage("Veuillez saisir un numéro de décision");
                    return false;
                }

                return {
                    decisionRef,
                    decisionFile
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('accepted-form');
                const formData = new FormData(form);
                formData.append('decision_ref', result.value.decisionRef);
                if (result.value.decisionFile) {
                    formData.append('decision_file', result.value.decisionFile);
                }

                fetch(form.action, {
                    method: "PUT",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        Swal.fire("Accepté !", "Recours recevable avec succès.", "success")
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
