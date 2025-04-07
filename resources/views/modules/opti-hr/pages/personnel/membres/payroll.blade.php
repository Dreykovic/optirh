
@extends('modules.opti-hr.pages.base')
@section('plugins-style')
<style>
    .form-text {
            font-size: 0.875em;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .required:after {
            content: " *";
            color: red;
        }
</style>
@endsection
@section('admin-content')

<div class="card m-auto p-2" style="width: 40rem;">
  <div class="card-body">
    <h5 class="card-title">Envoi des bulletins de paie</h5>
    <h6 class="card-subtitle mb-2 text-muted">Dernier Envoi : {{$lastUploadDate}}</h6>
    <input type="file" class='form-control mt-4 ' id="pdfInput" accept="application/pdf">
    <div class="form-text required mb-4">Chargez le bullettin de paie Sage Paie</div>

        <button class='btn btn-primary w-auto text-uppercase' onclick="processPDF()" id="submitBtn">
            <span class="normal-status">
                Envoyer
            </span>
            <span class="indicateur d-none">
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Un Instant...
            </span>

        </button>
  </div>
</div>

    <!-- <h2>Envoi des bulletins de paie</h2>
    <div class='d-flex'>
        <input type="file" class='form-control' id="pdfInput" accept="application/pdf">
        <button class='btn btn-primary' onclick="processPDF()">
            <span class="normal-status">
                Envoyer
            </span>
            <span class="indicateur d-none">
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Un Instant...
            </span>

        </button>
        <div>Dernier Envoi</div>
    </div> -->
    


@endsection
@push('plugins-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.9.179/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>
@endpush
@push('js')
<script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.9.179/pdf.worker.min.js';
          async function extractTextFromPage(pdf, pageIndex) {
            const page = await pdf.getPage(pageIndex + 1);
            const textContent = await page.getTextContent();
            let text = textContent.items.map(item => item.str).join(" ");

            // Trouver "M " ou "Mme " suivi de mots en majuscules
            let match = text.match(/(?:M|Mme)\s+([A-ZÉÈÀÇÂÊÎÔÛÄËÏÖÜŸ\s]+)/);

            if (match) {
                // Séparer les mots et ne garder que les 2 ou 3 premiers
                let words = match[1].trim().split(/\s+/);
                let name = words.slice(0, 2).join("_"); // Prend 2 mots, ajustable à 3 si nécessaire
                return name;
            }

            return `page_${pageIndex + 1}`; // Si le nom n'est pas trouvé
        }
        async function processPDF() {
            const fileInput = document.getElementById('pdfInput');
            //
            const btn = document.getElementById('submitBtn');
            const normalStatus = btn.querySelector('.normal-status');
            const spinnerStatus = btn.querySelector('.indicateur');

            // Afficher le spinner
            normalStatus.classList.add('d-none');
            spinnerStatus.classList.remove('d-none');
            btn.disabled = true;
            //
            if (!fileInput.files.length) {
                Swal.fire({
                    icon: "warning",
                    title: "Aucun fichier sélectionné",
                    text: "Veuillez sélectionner un Bullettin de Paie.",
                });
                normalStatus.classList.remove('d-none');
                spinnerStatus.classList.add('d-none');
                btn.disabled = false;

                return;
            }

            const file = fileInput.files[0];
            const reader = new FileReader();

            reader.onload = async function () {
                const pdfBytes = new Uint8Array(reader.result);
                const pdfDoc = await PDFLib.PDFDocument.load(pdfBytes);
                const pdf = await pdfjsLib.getDocument({ data: pdfBytes }).promise;
                const totalPages = pdfDoc.getPageCount();
                
                const formData = new FormData();

                for (let i = 0; i < totalPages; i++) {
                    const employeeName = await extractTextFromPage(pdf, i);
                    const newPdf = await PDFLib.PDFDocument.create();
                    const [copiedPage] = await newPdf.copyPages(pdfDoc, [i]);
                    newPdf.addPage(copiedPage);

                    const newPdfBytes = await newPdf.save();
                    const blob = new Blob([newPdfBytes], { type: "application/pdf" });

                    formData.append("files[]", blob, `${employeeName}.pdf`);
                }

                // Envoyer tous les fichiers en une seule requête
                await sendFilesToServer(formData);
                // ✅ Rétablir l’état du bouton et masquer le spinner
                normalStatus.classList.remove('d-none');
                spinnerStatus.classList.add('d-none');
                btn.disabled = false;

                // ✅ Réinitialiser l'input fichier
                fileInput.value = "";
                //
            };

            reader.readAsArrayBuffer(file);
        }

       
        //  async function sendFilesToServer(formData) {
        //     try {
        //         const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        //         const response = await fetch("/opti-hr/files/invoices", {
        //             method: "POST",
        //             headers: {
        //                 "X-CSRF-TOKEN": csrfToken
        //             },
        //             body: formData,
        //         });

        //         const result = await response.json();
        //         if (response.ok) {
        //             console.log("Fichiers envoyés avec succès :", result);
        //             Swal.fire({
        //             icon: "success",
        //             title: "Succès !",
        //             text: "Les bullettins ont été traités avec succès.",
        //         });
        //         } else {
        //             console.error("Erreur lors de l'envoi :", result);
        //             // alert("Erreur lors de l'envoi des fichiers.");
        //             Swal.fire({
        //             icon: "error",
        //             title: "Erreur !",
        //             text: "Erreur lors de l'envoi des fichiers.",
        //         });
        //         }
        //     } catch (error) {
        //         console.error("Erreur réseau :", error);
        //     }
        // }
        async function sendFilesToServer(formData) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch("/opti-hr/files/invoices", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: formData,
                });

                const result = await response.json();

                if (response.ok) {
                    console.log("Fichiers envoyés avec succès :", result);

                    let successCount = 0;
                    let errorRows = '';
                    let successRows = '';

                    result.forEach(item => {
                        if (item.success) {
                            successCount++;
                            // successRows += `<tr><td>${item.employee}</td><td>${item.file}</td></tr>`;
                        } else {
                            errorRows += `<tr><td>${item.file}</td><td>${item.message}</td></tr>`;
                        }
                    });

                    let htmlContent = `
                        <div style="text-align:center;">
                            <p class='text-success'><span>${successCount} bullettins envoyés avec succès.</span></p>

                            ${errorRows ? `
                                <p class=""><strong>Envois échoués :</strong></p>
                                <table class="table table-bordered table-sm text-danger">
                                    <thead><tr><th>Fichier</th><th>Erreur</th></tr></thead>
                                    <tbody>${errorRows}</tbody>
                                </table>` : ''
                            }
                        </div>
                    `;

                    Swal.fire({
                        icon: "success",
                        title: "Résultat d'envoi",
                        html: htmlContent,
                        // width: '60rem'
                    });

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erreur !",
                        text: "Erreur lors de l'envoi des fichiers.",
                    });
                }
            } catch (error) {
                console.error("Erreur réseau :", error);
                Swal.fire({
                    icon: "error",
                    title: "Erreur réseau !",
                    text: error.message,
                });
            }
        }


</script>
@endpush


