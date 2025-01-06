@extends('pages.admin.base')
@section('plugins-style')

@endsection
@section('admin-content')
<div class='d-flex justify-content-between'>

    <div class='col-sm-8 col-lg-8 col-xl-8 mx-2'>
        <div class="card mb-3">
            <div class="card-body">
                <div class='d-flex justify-content-between mb-3'>
                    <div class='d-flex justify-content-between align-items-center'>
                        <span>Afficher</span>
                        <select id="limitSelect" class='form-select mx-2'>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                        </select>
                        <span>éléments</span>
                    </div>
                    <div class='d-flex justify-content-between align-items-center'>
                        <select id="directorInput" class='btn btn-secondary me-1 mt-1 w-sm-100'>
                            @foreach($departments as $dept)
                            <option value="{{$dept->id}}">{{$dept->name}}</option>
                            @endforeach
                            <option value="" selected>Directions</option>
                        </select>
                    </div>
                    <div class='d-flex justify-content-between align-items-center'>
                        <label for="searchInput" class='me-2'>Rechercher: </label>
                        <input type="text" id="searchInput" placeholder="Rechercher" class='form-control me-2'>
                    </div>
                </div>

                        <table id="paies" class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Employés</th>
                                    <th>Codes</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div id="pagination" class='mt-3'></div>
            </div>
        </div>
    </div>

    <div class='col-sm-4 col-lg-4 col-xl-4 '>
        <div class="card mb-3">
            <div class="card-body">
            <form action="/files/upload" method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <h4 for="files">Nouveaux Factures</h4>
                </div>
                    <input type="file" name="files[]" id="files" class='form-control mb-3' accept=".pdf" multiple>
                    <div class="card-footer ">
                        <button type="submit" class='btn btn-primary'>Envoyer</button>
                    </div>
               </form>
                <!--  -->
            </div>
        </div>
    </div>

</div>
@endsection
@push('plugins-js')

@endpush
@push('js')
<script src="{{ asset('app-js/employee/paginator.js') }}"></script>


<script>

    const paginator = new Paginator({
        apiUrl: '/membres/list', 
        renderElement: document.getElementById('paies'),
        searchInput: document.getElementById('searchInput'), // Input de recherche
        department: document.getElementById('directorInput'),
        limitSelect: document.getElementById('limitSelect'), // Sélecteur de limite
        paginationElement: document.getElementById('pagination'), // Élément pour la pagination
    renderCallback: (employees) => {
        const tableBody = document.querySelector('#paies tbody');
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
        <td>
            ${employee.code}
        </td>
      
    `;

    tableBody.appendChild(row);
});

        }
    }
});
</script>

@endpush