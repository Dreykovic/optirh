

    const paginator = new Paginator({
        apiUrl: '/opti-hr/membres/list', 
        renderElement: document.getElementById('membres'),
        searchInput: document.getElementById('searchInput'), // Input de recherche
        department: document.getElementById('directorInput'),
        limitSelect: document.getElementById('limitSelect'), // Sélecteur de limite
        paginationElement: document.getElementById('pagination'), // Élément pour la pagination
    renderCallback: (employees) => {
        const tableBody = document.querySelector('#membres tbody');
        tableBody.innerHTML = '';
        if (employees.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Aucun employé trouvé.</td></tr>';
        } else {
            employees.forEach(employee => {
                const row = document.createElement('tr');
            
                row.innerHTML = `
                            <td>
                                <div class="d-flex align-items-center">
                                <i class="icofont icofont-${employee.gender === 'FEMALE' ? 'businesswoman' : 'business-man-alt-2'} fs-3 avatar rounded-circle"></i>
                                    <span class='text-uppercase mx-2'>${employee.last_name}</span> <span class='text-capitalize'>${employee.first_name}</span>
                                </div>
                            </td>
                            <td>${employee.phone_number}</td>
                            <td>${employee.email}</td>
                            <td>${employee.address1 ?? ''}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/opti-hr/membres/pages/${employee.id}">Détails</a></li>
                                        <!-- <li><button class="dropdown-item text-danger">Supprimer</button></li>-->
                                    </ul>
                                </div>
                            </td>
                        `;

                tableBody.appendChild(row);
            });
        }
    }
});

