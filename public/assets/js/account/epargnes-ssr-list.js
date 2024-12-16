
$(document).ready(function() {
    // Fonction pour vérifier si DataTable est déjà initialisé
    function isDataTableInitialized(tableId) {
        return $.fn.dataTable.isDataTable(tableId);
    }
    
    var TOKEN = $('meta[name="csrf-token"]').attr('content');
    var account_id = document.getElementById("account_id").value;
    
    function loadData(initial_date, finale_date) {
        // Vérifiez si DataTable est déjà initialisé, si oui, détruisez-le
        if (isDataTableInitialized('#epargnes_table')) {
            $('#epargnes_table').DataTable().destroy();
        }

        // Initialisation de DataTable
        var table = new DataTable('#epargnes_table', {
            responsive: true,
            serverSide: true,
            paging: true,
            processing: true,
            stateSave: true,
            'lengthMenu': [10, 25, 50, 100, 150, 200, 300, 500],
            searching: true,
            sort: true,
            ajax: {
                url: "/accounts/epargnes/ssr-list",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': TOKEN
                },
                data: {
                    "epargne_from_date": initial_date,
                    "epargne_to_date": finale_date,
                    "account_id" : account_id,
                },
            },
            language: {
                // processing: "Traitement en cours...",
                processing: "",
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
                
                { data: 'transaction_date', visible: true, title: "Date" },
                {
                    data: 'type', visible: true, title: "Transaction",
                    render: function(data, type, row) {
                        if (row.type == "WITHDRAWAL") {
                            return '<span class="badge bg-success">WITHDRAWAL</span>';
                        } else if (row.type == "DEPOSIT") {
                            return '<span class="badge bg-lavender-purple">DEPOSIT</span>';
                        }else if(row.type == "CONTRIBUTION"){
                            return '<span class="badge bg-info">CONTRIBUTION</span>';
                        }else{
                            return '<span class="badge bg-info">CONTRIBUTION</span>';
                        }
                    }
                },
                {
                    data: 'account_type', visible: true, title: "Compte",
                    render: function(data, type, row) {
                        if (row.account_type == "TONTINE") {
                            return '<span class="badge bg-success">TONTINE</span>';
                        } else if (row.account_type == "SAVINGS") {
                            return '<span class="badge bg-lavender-purple">SAVINGS</span>';
                        }else{
                            return '<span class="badge bg-info">MAIN</span>';
                        }
                    }
                },
                {
                    data: 'amount', visible: true, title: "Montant",
                    render: function(data, type, row) {
                        return '<span class="text-primary">' + row.amount + '</span>';
                    }
                },
                {
                    data: 'status', visible: true, title: "Status",
                    render: function(data, type, row) {
                        if (row.status == "COMPLETED") {
                            return '<span class="badge bg-warning">completed</span>';
                        } else if (row.status == "REJECTED") {
                            return '<span class="badge bg-danger">rejected</span>';
                        } else if (row.status == "FAILED") {
                            return '<span class="badge bg-danger">failed</span>';
                        } else if (row.status == "CANCELLED") {
                            return '<span class="badge bg-info">cancelled</span>';
                        } else if (row.status == "REFUNDED") {
                            return '<span class="badge bg-danger">refunded</span>';
                        } 
                    }
                },
                { data: 'description', visible: true, title: "Note" },
               
            ],
            columnDefs: [
                { targets: "_all", className: 'dt-right' }
            ],
        });
    }

    // Initialisation du tableau
    loadData();

    // Filtrage avec les dates
    $('form#epargne_date_filter').on('submit', function(e) {
        e.preventDefault();
        var from_date = $('#epargne_from_date').val();
        var to_date = $('#epargne_to_date').val();
        loadData(from_date, to_date);
    });

    $('#epargne_from_date').on('change', function() {
        $('#epargne_to_date').removeAttr("readonly");
        $('#epargne_to_date').attr("min", $(this).val());
        $('#epargne_to_date').val($(this).val());
    });
});
