<!-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traitement des bulletins</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.16.0/pdf-lib.min.js"></script>
</head>
<body>
    <h2>Importer un bulletin de paie</h2>
    <input type="file" id="pdfInput" accept="application/pdf">
    <button onclick="processPDF()">Traiter le PDF</button>

    <script>
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
                const totalPages = pdfDoc.getPageCount();

                for (let i = 0; i < totalPages; i++) {
                    const newPdf = await PDFLib.PDFDocument.create();
                    const [copiedPage] = await newPdf.copyPages(pdfDoc, [i]);
                    newPdf.addPage(copiedPage);

                    const newPdfBytes = await newPdf.save();
                    const blob = new Blob([newPdfBytes], { type: "application/pdf" });

                    // Téléchargement automatique
                    const a = document.createElement("a");
                    a.href = URL.createObjectURL(blob);
                    a.download = `bulletin_page_${i + 1}.pdf`;
                    a.click();
                }

                alert("Le PDF a été divisé avec succès !");
            };

            reader.readAsArrayBuffer(file);
        }
    </script>
</body>
</html> -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traitement des bulletins</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.16.0/pdf-lib.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
</head>
<body>
    <h2>Importer un bulletin de paie</h2>
    <input type="file" id="pdfInput" accept="application/pdf">
    <button onclick="processPDF()">Traiter le PDF</button>

    <script>
       
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

                for (let i = 0; i < totalPages; i++) {
                    const employeeName = await extractTextFromPage(pdf, i);
                    const newPdf = await PDFLib.PDFDocument.create();
                    const [copiedPage] = await newPdf.copyPages(pdfDoc, [i]);
                    newPdf.addPage(copiedPage);

                    const newPdfBytes = await newPdf.save();
                    const blob = new Blob([newPdfBytes], { type: "application/pdf" });

                    // Téléchargement automatique
                    const a = document.createElement("a");
                    a.href = URL.createObjectURL(blob);
                    a.download = `${employeeName}.pdf`;
                    a.click();
                }

                alert("Le PDF a été divisé et nommé avec succès !");
            };

            reader.readAsArrayBuffer(file);
        }
    </script>
</body>
</html>

