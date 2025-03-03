class Paginator {
    constructor(options) {
        this.currentPage = 1;
        this.totalPages = 1;
        this.totalItems = 0;

        // Options configurables
        this.apiUrl = options.apiUrl; // URL de l'API
        this.renderElement = options.renderElement; // Élément DOM pour afficher les données
        this.renderCallback = options.renderCallback; // Fonction de rendu des données
        this.searchInput = options.searchInput || null; // Input pour la recherche
        this.limitSelect = options.limitSelect || null; // Select pour le nombre d'éléments par page
        this.extraParams = options.extraParams || {}; // Paramètres supplémentaires pour la requête
        this.paginationElement = options.paginationElement || null; // Élément DOM pour la pagination
        this.department = options.department || null; // Input pour la recherche
        this.startDate = options.startDate || null;
        this.endDate = options.endDate || null;
        this.filterContainer = options.filterContainer || null;
       

        this.init();
    }

    // Initialiser les écouteurs d'événements
    init() {
        if (this.searchInput) {
            this.searchInput.addEventListener('input', () => this.loadData());
        }

        if (this.department) {            
            this.department.addEventListener('change', () => this.loadData());
        }

        if (this.limitSelect) {
            this.limitSelect.addEventListener('change', () => this.loadData());
        }
       
        if (this.filterContainer) {
            this.filterContainer.addEventListener('change', () => this.loadData());
        }

        if (this.startDate && this.endDate) {
            this.startDate.addEventListener('change', () => this.loadData());
            this.endDate.addEventListener('change', () => this.loadData());
        }
        // if (this.startDate && this.endDate==null) {
        //     endDate = startDate;
        //     this.startDate.addEventListener('change', () => this.loadData());
        // }


        this.loadData();
    }

    // Charger les données depuis l'API
    loadData() {
        const search = this.searchInput ? this.searchInput.value : '';
        const limit = this.limitSelect ? this.limitSelect.value : 10;
        const deptValue = this.department ? this.department.value : '';
        const startDateValue = this.startDate ? this.startDate.value : '';
        const endDateValue = this.endDate ? this.endDate.value : '';

       // Récupérer **toutes** les cases cochées avec name="filterStatus"
        const statusValues = [...document.querySelectorAll('input[name="filterStatus"]:checked')].map(el => el.value);
        // console.log('start date : ' + startDate.value);
        // console.log('end date : ' + endDate.value);
        

        const params = new URLSearchParams({
            search,
            limit,
            deptValue,
            startDate: startDateValue,
            endDate: endDateValue,
            statusOptions: statusValues.join(','),
            page: this.currentPage,
            ...this.extraParams,
        });
        console.log("Paramètres envoyés :", params.toString());

        fetch(`${this.apiUrl}?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                console.log("Réponse API :", data); // Ajoute ce log
                this.totalItems = data.total;
                this.totalPages = data.last_page;

                // Appeler la fonction de rendu
                this.renderCallback(data.data);

                // Mettre à jour la pagination
                if (this.paginationElement) {
                    this.renderPagination();
                }
            })
            .catch(error => console.error('Erreur lors du chargement des données:', error));
    }

    // Rendre la pagination
    renderPagination() {
        if (!this.paginationElement) return;

        this.paginationElement.innerHTML = '';

        const prevPage = this.currentPage > 1
            ? `<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="paginator.changePage(${this.currentPage - 1})">Précédent</a></li>`
            : '';

        const nextPage = this.currentPage < this.totalPages
            ? `<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="paginator.changePage(${this.currentPage + 1})">Suivant</a></li>`
            : '';

        this.paginationElement.innerHTML = `
            <ul class="pagination">
                ${prevPage}
                <li class="page-item disabled"><span class="page-link">Page ${this.currentPage} sur ${this.totalPages}</span></li>
                ${nextPage}
            </ul>
        `;
    }

    // Changer de page
    changePage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
            this.loadData();
        }
    }
}
