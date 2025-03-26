

document.querySelector('table').addEventListener('click', function(event) {
    if (event.target.closest('.job')) {
        const button = event.target.closest('.job');
        const jobId = button.dataset.bsJobId; // Récupère la valeur de data-bs-job-id
        console.log('Job ID:', jobId);

        const paginator = new Paginator({
            apiUrl: `/opti-hr/api/membres/job/${jobId}`, // URL de l'API
            renderElement: document.getElementById('employee_list'), // Élément où afficher les données
            renderCallback: renderFiles, // Fonction pour rendre les fichiers        
        });

    }

    function renderFiles(emps) {
            const empList = document.getElementById('employee_list');
            empList.innerHTML = '';

            if (emps.length === 0) {
                empList.innerHTML = '<div class="alert alert-warning">Aucun employe trouvé.</div>';
            } else {
                emps.forEach(emp => {
                    const empElement = document.createElement('li');
                    empElement.className = 'py-2 d-flex align-items-center border-bottom';
                    empElement.innerHTML = `
                        <i class="icofont icofont-${emp.gender === 'FEMALE' ? 'businesswoman' : 'business-man-alt-2'} fs-3 avatar rounded-circle"></i>
                       <span class='text-uppercase'>${emp.last_name} ${emp.first_name}</span>
                    `;
                    empList.appendChild(empElement);
                });
            }
        }
});


