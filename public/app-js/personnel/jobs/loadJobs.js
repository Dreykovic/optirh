
    function loadJobs(departmentId) {
        // Vérifie si un département a été sélectionné
        if (!departmentId) {
            document.getElementById('job').innerHTML = '<option selected>choisir</option>';
            return;
        }

        // Effectue une requête AJAX
        fetch(`/opti-hr/api/jobs/${departmentId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Erreur lors du chargement des jobs");
                }
                return response.json();
            })
            .then(data => {
                // Met à jour le champ "Poste"
               
                const jobSelect = document.getElementById('job');
                jobSelect.innerHTML = '<option selected>choisir</option>';
                data.forEach(job => {
                    jobSelect.innerHTML += `<option value="${job.id}">${job.title}</option>`;
                });
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert("Impossible de charger les postes....");
            });
    }

