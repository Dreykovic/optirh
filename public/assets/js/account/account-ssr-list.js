
$(document).ready(function() {
    // Fonction pour vérifier si DataTable est déjà initialisé
    function isDataTableInitialized(tableId) {
        return $.fn.dataTable.isDataTable(tableId);
    }
    
    var TOKEN = $('meta[name="csrf-token"]').attr('content');
    
    function loadData(initial_date, finale_date) {
        // Vérifiez si DataTable est déjà initialisé, si oui, détruisez-le
        if (isDataTableInitialized('#accounts_table')) {
            $('#accounts_table').DataTable().destroy();
        }

        // Initialisation de DataTable
        var table = new DataTable('#accounts_table', {
            responsive: true,
            serverSide: true,
            paging: true,
            processing: true,
            stateSave: true,
            'lengthMenu': [10, 25, 50, 100, 150, 200, 300, 500],
            searching: true,
            sort: true,
            ajax: {
                url: "/accounts/ssr-list",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': TOKEN
                },
                data: {
                    "from_date": initial_date,
                    "to_date": finale_date,
                },
            },
            language: {
                processing: "",
                // processing: "Traitement en cours...",
                search: "Rechercher&nbsp;:",
                lengthMenu: "Afficher _MENU_ &eacute;l&eacute;ments",
                info: "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                infoEmpty: "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                infoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                loadingRecords: "Chargement en cours...",
                zeroRecords: "Aucun &eacute;l&eacute;ment &agrave; afficher",
                emptyTable: "Aucune donnée disponible dans le tableau",
                paginate: {
                    first: "Premier",
                    previous: "Pr&eacute;c&eacute;dent",
                    next: "Suivant",
                    last: "Dernier"
                },
            },
            columns: [
                { data: 'lastname', visible: true, title: "Nom ",
                  render: function(data, type, row) {
                        return '<span class="text-uppercase">' + row.lastname + '</span>';
                    }
                 },
                { data: 'firstname', visible: true, title: "Prénoms",
                  render: function(data, type, row) {
                        return '<span class="text-capitalize">' + row.firstname + '</span>';
                    }
                 },
                { data: 'phone', visible: true, title: "Numéro" },
                {
                    data: 'type', visible: true, title: "Compte",
                    render: function(data, type, row) {
                        if (row.type == "TONTINE") {
                            return '<span class="badge bg-success">TONTINE</span>';
                        } else if (row.type == "SAVINGS") {
                            return '<span class="badge bg-lavender-purple">SAVINGS</span>';
                        }else{
                            return '<span class="badge bg-info">MAIN</span>';
                        }
                    }
                },
                {
                    data: 'balance', visible: true, title: "Solde",
                    render: function(data, type, row) {
                        return '<span class="text-primary">' + row.balance + '</span>';
                    }
                },
                {
                    data: 'state', visible: true, title: "Status",
                    render: function(data, type, row) {
                        if (row.state == "ACTIVE") {
                            return '<span class="badge bg-warning">active</span>';
                        } else if (row.state == "CLOSED") {
                            return '<span class="badge bg-danger">closed</span>';
                        } else if (row.state == "INACTIVE") {
                            return '<span class="badge bg-danger">inactive</span>';
                        } else if (row.state == "SUSPENDED") {
                            return '<span class="badge bg-info">suspended</span>';
                        } else if (row.state == "BLOCKED") {
                            return '<span class="badge bg-danger">blocked</span>';
                        } else {
                            return '<span class="badge bg-success">terminé</span>';
                        }
                    }
                },
                { data: 'address1', visible: true, title: "Adresse",
                  render: function(data, type, row) {
                        return '<span class="text-capitalize">' + row.address1 + '</span>';
                    }
                 }
            ],
            columnDefs: [
                { targets: "_all", className: 'dt-right' }
            ],
            rowCallback: function(row, data) {
                // Ajoute l'événement de clic sur la ligne pour rediriger
                $(row).on('click', function() {
                    window.location.href = '/accounts/detail/' + data.id;
                });
            }
        });
    }

    // Initialisation du tableau
    loadData();
    // Filtrage avec les dates
    $('form#date_filter').on('submit', function(e) {
        e.preventDefault();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        $('#accounts_table').DataTable().destroy(); // Detruire avant de recharger
        loadData(from_date, to_date);
    });

    $('#from_date').on('change', function() {
        $('#to_date').removeAttr("readonly");
        $('#to_date').attr("min", $(this).val());
        $('#to_date').val($(this).val());
    });
});
