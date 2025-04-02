
// document.addEventListener('DOMContentLoaded', function () {
//     document.getElementById('accepted-btn').addEventListener('click', function () {
//         Swal.fire({
//             title: "Êtes-vous sûr de la recevabilité du recours ?",
//             icon: "warning",
//             showCancelButton: true,
//             confirmButtonColor: "#28a745",
//             cancelButtonColor: "#3085d6",
//             confirmButtonText: "Oui, Accepter !",
//             cancelButtonText: "Annuler",
//             html: `
//                 <div>
//                     <label class='form-label'>Décision N°:</label>
//                     <input class='form-control' id='suspended-ref' name='suspended_ref' type="text">
//                 </div>
//                 <div>
//                     <label class='form-label'>Fichier</label>
//                     <input class='form-control' id='suspended-file' name='suspended_file' type="file">
//                 </div>
//             `,
//             preConfirm: () => {
//                 const decisionRef = document.getElementById('suspended-ref').value;
//                 const decisionFile = document.getElementById('suspended-file').files[0];
                
//                 if (!decisionRef) {
//                     Swal.showValidationMessage("Veuillez saisir un numéro de décision");
//                     return false;
//                 }

//                 return {
//                     decisionRef,
//                     decisionFile
//                 };
//             }
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 const form = document.getElementById('accepted-form');
//                 const formData = new FormData();
                
//                 // Ajouter le token CSRF
//                 formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
//                 formData.append('_method', 'PUT');

//                 // Ajouter les valeurs récupérées depuis la boîte de dialogue
//                 formData.append('suspended_ref', result.value.decisionRef);
//                 if (result.value.decisionFile) {
//                     formData.append('suspended_file', result.value.decisionFile);
//                 }

//                 fetch(form.action, {
//                     method: "POST", // PUT peut parfois poser problème avec FormData
//                     body: formData
//                 })
//                 .then(response => response.json())
//                 .then(data => {
//                     if (data.ok) {
//                         Swal.fire("Accepté !", "Recours recevable avec succès.", "success")
//                         .then(() => {
//                             window.location.reload();
//                         });
//                     } else {
//                         Swal.fire("Erreur !", data.message, "error");
//                     }
//                 })
//                 .catch(() => {
//                     Swal.fire("Erreur !", "Une erreur s'est produite.", "error");
//                 });
//             }
//         });
//     });
// });


document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('accepted-btn').addEventListener('click', function () {
        Swal.fire({
            title: "Êtes-vous sûr de la recevabilité de ce recours ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Oui, Recevable !",
            cancelButtonText: "Annuler",
            html: `
                <div>
                    <label class='form-label'>Décision N°:</label>
                    <input class='form-control' id='suspended-ref' name='suspended_ref' type="text">
                </div>
                <div>
                    <label class='form-label'>Fichier</label>
                    <input class='form-control' id='suspended-file' name='suspended_file' type="file">
                </div>
            `,
            preConfirm: () => {
                const decisionRef = document.getElementById('suspended-ref').value;
                const decisionFile = document.getElementById('suspended-file').files[0];

                if (!decisionRef.trim()) {
                    Swal.showValidationMessage("Veuillez saisir un numéro de décision");
                    return false;
                }

                return {
                    decisionRef,
                    decisionFile
                };
            },
           
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('suspended-form');
                const formData = new FormData(form);
                formData.append('suspended_ref', result.value.decisionRef);
                if (result.value.decisionFile) {
                    formData.append('suspended_file', result.value.decisionFile);
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
