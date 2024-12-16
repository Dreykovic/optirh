
$(document).ready(function() {
    // Fonction pour vérifier si DataTable est déjà initialisé
    function isDataTableInitialized(tableId) {
        return $.fn.dataTable.isDataTable(tableId);
    }
    
    var TOKEN = $('meta[name="csrf-token"]').attr('content');
    var account_id = document.getElementById("account_id").value;
    
    function loadData(initial_date, finale_date) {
        // Vérifiez si DataTable est déjà initialisé, si oui, détruisez-le
        if (isDataTableInitialized('#credits_table')) {
            $('#credits_table').DataTable().destroy();
        }

        // Initialisation de DataTable
        var table = new DataTable('#credits_table', {
            responsive: true,
            serverSide: true,
            paging: true,
            processing: true,
            stateSave: true,
            'lengthMenu': [10, 25, 50, 100, 150, 200, 300, 500],
            searching: true,
            sort: true,
            ajax: {
            url: "/accounts/credits/ssr-list",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': TOKEN
                },
                data: {
                    "credit_from_date": initial_date,
                    "credit_to_date": finale_date,
                    "account_id" : account_id,
                },
            },
            language: {
                //processing: "Traitement en cours...",
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
                
                { data: 'agreement_date', visible: true, title: "Date d'agrement" },
                { data: 'due_date', visible: true, title: "Date d'échéance" },
                {
                    data: 'balance', visible: true, title: "Montant accordé",
                    render: function(data, type, row) {
                        return '<span class="text-primary">' + row.balance + '</span>';
                    }
                },
                {
                    data: 'interest_rate', visible: true, title: "Taux interet ",
                    render: function(data, type, row) {
                        return '<span class="text-secondary">' + row.interest_rate + '</span>';
                    }
                },
                
                {
                    data: 'state', visible: true, title: "Status",
                    render: function(data, type, row) {
                        if (row.state == "ACTIVE") {
                            return '<span class="badge bg-warning">completed</span>';
                        } else if (row.state == "DEFAULTED") {
                            return '<span class="badge bg-danger">rejected</span>';
                        } else if (row.state == "CHANGE_OFF") {
                            return '<span class="badge bg-danger">failed</span>';
                        } else if (row.state == "CLOSED") {
                            return '<span class="badge bg-info">cancelled</span>';
                        } else if (row.state == "OVERDUE") {
                            return '<span class="badge bg-danger">refunded</span>';
                        } 
                    }
                },
               
            ],
            columnDefs: [
                { targets: "_all", className: 'dt-right' }
            ],
        });
    }

    // Initialisation du tableau
     loadData();

   // Filtrage avec les dates
    $('form#credit_date_filter').on('submit', function(e) {
        e.preventDefault();
        var from_date = $('#credit_from_date').val();
        var to_date = $('#credit_to_date').val();
        loadData(from_date, to_date);
    });

    $('#credit_from_date').on('change', function() {
        $('#credit_to_date').removeAttr("readonly");
        $('#credit_to_date').attr("min", $(this).val());
        $('#credit_to_date').val($(this).val());
    });
});
