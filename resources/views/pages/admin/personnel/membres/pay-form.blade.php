@extends('pages.admin.base')
@section('plugins-style')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .tree ul {
      list-style-type: none;
      margin-left: 20px;
      padding-left: 0;
    }
    .tree ul li {
      margin: 5px 0;
    }
    .tree-toggle {
      cursor: pointer;
      display: flex;
      align-items: center;
    }
    .tree-toggle::before {
      content: "\1F4C1"; /* Closed folder icon */
      display: inline-block;
      margin-right: 5px;
      transition: transform 0.2s;
      font-size:40px
    }
    .tree ul .tree-open::before {
      content: "\1F4C2"; /* Open folder icon */
    }
    .tree ul .d-none {
      display: none;
    }
</style>
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

                    <form action="/files/upload" method="post" enctype="multipart/form-data">
                        @csrf
                        <table id="paies" class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Employés</th>
                                    <th>Factures</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div id="pagination" class='mt-3'></div>
                        <button type="submit" class="btn btn-primary mt-3">Envoyer les factures</button>
                    </form>

            </div>
        </div>
    </div>

    <div class='col-sm-4 col-lg-4 col-xl-4 '>
        <div class="card mb-3">
            <div class="card-body">
                <div class="tree">
                    <ul>
                        <li>
                        <span class="tree-toggle">Cours</span>
                        <ul class="d-none">
                            <!-- <li>ANCIENNE CONFIGURATION (2019-2020)</li>
                            <li>PERSONNEL IPNET</li> -->
                            <li>
                            <span class="tree-toggle">PRESENTIEL</span>
                            <ul class="d-none">
                                <li>LICENCE</li>
                                <li>MASTER</li>
                                <li>CERTIFICATS</li>
                                <li>LICENCE CYCLE COURT</li>
                            </ul>
                            </li>
                            <!-- <li>E-LEARNING</li>
                            <li>CERTIFICATIONS TRAINING</li> -->
                        </ul>
                        </li>
                    </ul>
                </div>
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
                ${employee.first_name} ${employee.last_name}
            </div>
        </td>
        <td>
            <input type="file" class="form-control" name="files[${employee.id}]" placeholder="Sélectionner un fichier" required accept=".pdf">
        </td>
        <td>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/membres/${employee.id}">Détails</a></li>
                    <li><button class="dropdown-item text-danger">Supprimer</button></li>
                </ul>
            </div>
        </td>
    `;

    tableBody.appendChild(row);
});

        }
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelectorAll('.tree-toggle').forEach(function (toggle) {
      toggle.addEventListener('click', function () {
        const parentLi = toggle.parentElement;
        const nestedUl = parentLi.querySelector('ul');
        
        if (nestedUl) {
          nestedUl.classList.toggle('d-none');
          toggle.classList.toggle('tree-open');
        }
      });
    });
  </script>
@endpush