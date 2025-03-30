
@extends('modules.opti-hr.pages.base')
@section('plugins-style')
@endsection
@section('admin-content')

    <h2>Envoi des bulletins de paie</h2>
    <div class='d-flex'>
        <input type="file" class='form-control' id="pdfInput" accept="application/pdf">
        <button class='btn btn-primary' onclick="processPDF()">Envoyer</button>
    </div>
    
 



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
            if (!fileInput.files.length) {
                alert("Veuillez sélectionner un fichier PDF.");
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
            };

            reader.readAsArrayBuffer(file);
         }

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
                alert("Les fichiers ont été envoyés au serveur !");
            } else {
                console.error("Erreur lors de l'envoi :", result);
                alert("Erreur lors de l'envoi des fichiers.");
            }
        } catch (error) {
            console.error("Erreur réseau :", error);
        }
    }

 </script>
@endpush


