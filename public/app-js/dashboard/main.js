/**
 * OPTIRH Dashboard - JavaScript Principal
 * Gestion des graphiques, calendrier et interactions
 */

document.addEventListener('DOMContentLoaded', function () {
    // Initialisation des composants
    initCharts();
    initCalendar();
    initRefresh();
    initTooltips();
});

/**
 * Palette de couleurs professionnelle
 */
const COLORS = {
    primary: 'rgba(79, 70, 229, 0.8)',
    primaryLight: 'rgba(79, 70, 229, 0.1)',
    success: 'rgba(16, 185, 129, 0.8)',
    successLight: 'rgba(16, 185, 129, 0.1)',
    warning: 'rgba(245, 158, 11, 0.8)',
    warningLight: 'rgba(245, 158, 11, 0.1)',
    danger: 'rgba(239, 68, 68, 0.8)',
    dangerLight: 'rgba(239, 68, 68, 0.1)',
    info: 'rgba(59, 130, 246, 0.8)',
    infoLight: 'rgba(59, 130, 246, 0.1)',
    secondary: 'rgba(107, 114, 128, 0.8)',
    purple: 'rgba(139, 92, 246, 0.8)',
    pink: 'rgba(236, 72, 153, 0.8)',
    teal: 'rgba(20, 184, 166, 0.8)'
};

/**
 * Options communes pour les graphiques
 */
const CHART_OPTIONS = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                padding: 20,
                usePointStyle: true,
                font: {
                    family: "'Inter', 'Segoe UI', sans-serif",
                    size: 12
                }
            }
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            padding: 12,
            titleFont: { size: 14, weight: 'bold' },
            bodyFont: { size: 13 },
            cornerRadius: 8
        }
    }
};

/**
 * Initialise les graphiques Chart.js
 */
function initCharts() {
    // Graphique de repartition par departement
    const deptChart = document.getElementById('departmentChart');
    if (deptChart && typeof departmentChartData !== 'undefined') {
        const chartColors = [
            COLORS.primary,
            COLORS.success,
            COLORS.warning,
            COLORS.danger,
            COLORS.info,
            COLORS.purple,
            COLORS.pink,
            COLORS.teal,
            COLORS.secondary
        ];

        new Chart(deptChart, {
            type: 'doughnut',
            data: {
                labels: departmentChartData.labels || [],
                datasets: [{
                    data: departmentChartData.data || [],
                    backgroundColor: chartColors.slice(0, (departmentChartData.data || []).length),
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                ...CHART_OPTIONS,
                cutout: '65%',
                plugins: {
                    ...CHART_OPTIONS.plugins,
                    title: {
                        display: false
                    }
                }
            }
        });
    }

    // Graphique de repartition par genre
    const genderChartEl = document.getElementById('genderChart');
    if (genderChartEl && typeof genderChartData !== 'undefined') {
        new Chart(genderChartEl, {
            type: 'pie',
            data: {
                labels: ['Femme', 'Homme'],
                datasets: [{
                    data: genderChartData,
                    backgroundColor: [COLORS.danger, COLORS.info],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: CHART_OPTIONS
        });
    }
}

/**
 * Initialise le calendrier FullCalendar
 */
function initCalendar() {
    const calendarEl = document.getElementById('absenceCalendar');
    if (!calendarEl) return;

    // Verifier si les donnees du calendrier sont disponibles
    const events = typeof calendarEvents !== 'undefined' ? calendarEvents : [];
    const allEvents = typeof allCalendarEvents !== 'undefined' ? allCalendarEvents : events;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            list: 'Liste'
        },
        events: events,
        eventDisplay: 'block',
        dayMaxEvents: 3,
        eventClick: function (info) {
            // Redirection vers la page des absences
            window.location.href = window.dashboardCalendarUrl.replace('/absence-calendar', '/attendances/absences/requests');
        },
        eventDidMount: function (info) {
            // Ajouter un tooltip
            if (info.event.extendedProps.description) {
                info.el.setAttribute('title', info.event.extendedProps.description);
                info.el.setAttribute('data-bs-toggle', 'tooltip');
                new bootstrap.Tooltip(info.el);
            }
        }
    });

    calendar.render();

    // Stocker la reference du calendrier globalement
    window.dashboardCalendar = calendar;
    window.dashboardCalendarAllEvents = allEvents;
    window.dashboardCalendarApprovedEvents = events;

    // Toggle pour afficher toutes les absences
    const showAllToggle = document.getElementById('showAllAbsences');
    if (showAllToggle) {
        showAllToggle.addEventListener('change', function () {
            calendar.removeAllEvents();
            if (this.checked) {
                calendar.addEventSource(allEvents);
            } else {
                calendar.addEventSource(events);
            }
        });
    }
}

/**
 * Initialise la fonctionnalite de rafraichissement
 */
function initRefresh() {
    const refreshBtn = document.getElementById('refreshDashboard');
    if (!refreshBtn) return;

    refreshBtn.addEventListener('click', async function () {
        const icon = this.querySelector('i');
        const originalClass = icon.className;

        // Animation de rotation
        icon.className = 'icofont-refresh spin-animation';
        this.disabled = true;

        try {
            const response = await fetch(window.dashboardRefreshUrl || '/opti-hr/dashboard/refresh', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Erreur reseau');

            const data = await response.json();

            // Mettre a jour les statistiques
            if (data.stats) {
                updateStatCards(data.stats);
            }

            // Mettre a jour le calendrier
            if (window.dashboardCalendar && data.events) {
                window.dashboardCalendar.removeAllEvents();
                window.dashboardCalendar.addEventSource(data.events);
            }

            // Afficher le toast de succes
            showToast('success');

        } catch (error) {
            console.error('Erreur lors de l\'actualisation:', error);
            showToast('error');
        } finally {
            icon.className = originalClass;
            this.disabled = false;
        }
    });
}

/**
 * Met a jour les cartes de statistiques
 */
function updateStatCards(stats) {
    Object.entries(stats).forEach(([key, value]) => {
        const el = document.querySelector(`[data-stat="${key}"] .stat-value`);
        if (el) {
            // Animation de mise a jour
            el.classList.add('fade-in');
            el.textContent = typeof value === 'number' ? value.toLocaleString('fr-FR') : value;
            setTimeout(() => el.classList.remove('fade-in'), 300);
        }
    });
}

/**
 * Affiche un toast de notification
 */
function showToast(type) {
    const toastEl = document.getElementById('refreshToast');
    if (!toastEl) return;

    // Modifier le style selon le type
    if (type === 'error') {
        toastEl.classList.remove('bg-success');
        toastEl.classList.add('bg-danger');
        toastEl.querySelector('.toast-body').innerHTML =
            '<i class="icofont-warning me-2"></i>Erreur lors de l\'actualisation';
    } else {
        toastEl.classList.remove('bg-danger');
        toastEl.classList.add('bg-success');
        toastEl.querySelector('.toast-body').innerHTML =
            '<i class="icofont-check-circled me-2"></i>Tableau de bord actualise';
    }

    const toast = new bootstrap.Toast(toastEl, {
        autohide: true,
        delay: 3000
    });
    toast.show();
}

/**
 * Initialise les tooltips Bootstrap
 */
function initTooltips() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Animation CSS pour le rafraichissement
 */
(function () {
    const style = document.createElement('style');
    style.textContent = `
        .spin-animation {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .fade-in {
            animation: fadeIn 0.3s ease forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
})();
