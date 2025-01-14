
    const form = document.getElementById("modelAddForm");

    form.addEventListener("submit", async function (event) {
        event.preventDefault();

        const url = form.getAttribute("data-model-add-url");
        const formData = new FormData(form);

        try {
            const response = await fetch(url, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
            });

            const result = await response.json();

            if (result.requires_confirmation) {
                // SweetAlert2 pour la confirmation
                const confirmation = await Swal.fire({
                    title: 'Confirmation requise',
                    text: result.message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, continuer',
                    cancelButtonText: 'Annuler',
                });

                if (confirmation.isConfirmed) {
                    formData.append("force_create", true);

                    const forcedResponse = await fetch(url, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                        },
                    });

                    const forcedResult = await forcedResponse.json();

                    if (forcedResult.ok) {
                        await Swal.fire({
                            title: 'Succès !',
                            text: 'Créé avec succès !',
                            icon: 'success',
                        });
                        location.reload(); // Rafraîchir la page après succès
                    } else {
                        await Swal.fire({
                            title: 'Erreur',
                            text: forcedResult.message,
                            icon: 'error',
                        });
                    }
                }
            } else if (result.ok) {
                await Swal.fire({
                    title: 'Succès !',
                    text: 'Créé avec succès !',
                    icon: 'success',
                });
                location.reload();
            } else {
                await Swal.fire({
                    title: 'Erreur',
                    text: result.message,
                    icon: 'error',
                });
            }
        } catch (error) {
            console.error("Erreur lors de la soumission :", error);
            await Swal.fire({
                title: 'Erreur',
                text: 'Une erreur est survenue. Veuillez réessayer.',
                icon: 'error',
            });
        }
    });

