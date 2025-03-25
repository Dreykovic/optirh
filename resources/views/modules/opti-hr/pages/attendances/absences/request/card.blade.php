<!-- Improved Absence Request Item -->
<div class="absence-card mb-4 shadow-sm rounded-3 border-0 overflow-hidden">
    <!-- Accordion Header -->
    <div class="accordion-header absence-header" id="absenceRequestLine{{ $absence->id }}">
        <div class="card border-0">
            @include('modules.opti-hr.pages.attendances.absences.request.header')
        </div>
        <button class="accordion-button collapsed px-4 py-3 bg-light" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseAbsenceRequestLine{{ $absence->id }}" aria-expanded="false"
            aria-controls="collapseAbsenceRequestLine{{ $absence->id }}">
            <span class="btn-text">Voir les détails</span>
        </button>
    </div>

    <!-- Accordion Content -->
    <div id="collapseAbsenceRequestLine{{ $absence->id }}" class="accordion-collapse collapse"
        aria-labelledby="absenceRequestLine{{ $absence->id }}" data-bs-parent="#absenceRequestsList">
        <div class="card border-0">
            @include('modules.opti-hr.pages.attendances.absences.request.body')
        </div>
    </div>

    <!-- Footer with Action Buttons -->
    <div class="card border-0">
        @include('modules.opti-hr.pages.attendances.absences.request.footer')
    </div>
</div>

<!-- Comment Modal -->
@include('modules.opti-hr.pages.attendances.absences.request.comment')

<style>
    /* Custom styles for the absence management UI */
    .absence-card {
        transition: all 0.3s ease;
    }

    .absence-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0, 0, 0, .125);
    }

    .accordion-button::after {
        transition: all 0.3s ease;
    }

    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
    }

    .lift {
        transition: all 0.25s ease;
        border-radius: 0.375rem;
    }

    .lift:hover,
    .lift:focus {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .employee-avatar {
        width: 50px;
        height: 50px;
    }

    .absence-info-card,
    .absence-manager-card,
    .absence-reason-card {
        transition: all 0.3s ease;
    }

    .absence-info-card:hover,
    .absence-manager-card:hover,
    .absence-reason-card:hover {
        background-color: #f8f9fa !important;
    }

    .btn-success,
    .btn-danger,
    .btn-warning,
    .btn-primary {
        color: white;
    }

    .btn-outline-primary:hover {
        color: white;
    }

    .fw-medium {
        font-weight: 500;
    }

    .avatar {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .absence-card .card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .absence-card .card-header .text-end {
            display: block !important;
            width: 100%;
            margin-top: 1rem;
            text-align: left !important;
        }

        .absence-card .card-header .text-end .d-flex {
            justify-content: flex-start !important;
        }

        .absence-card .card-footer {
            flex-direction: column;
            gap: 1rem;
        }

        .absence-card .card-footer .d-flex {
            width: 100%;
            justify-content: center !important;
        }
    }
</style>

<!-- Add JavaScript for improved interactions -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to accordions
        const accordionButtons = document.querySelectorAll('.accordion-button');
        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.querySelector('.btn-text').textContent = isExpanded ? 'Voir les détails' :
                    'Masquer les détails';
            });
        });

        // Enhance buttons with ripple effect
        const actionButtons = document.querySelectorAll('.btn');
        actionButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!this.classList.contains('btn-close') && !this.classList.contains(
                        'accordion-button')) {
                    const ripple = document.createElement('div');
                    const rect = this.getBoundingClientRect();

                    ripple.style.position = 'absolute';
                    ripple.style.width = '1px';
                    ripple.style.height = '1px';
                    ripple.style.left = e.clientX - rect.left + 'px';
                    ripple.style.top = e.clientY - rect.top + 'px';
                    ripple.style.background = 'rgba(255, 255, 255, 0.4)';
                    ripple.style.borderRadius = '50%';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    ripple.style.pointerEvents = 'none';

                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.style.transform = 'scale(100)';
                        ripple.style.opacity = '0';

                        setTimeout(() => {
                            ripple.remove();
                        }, 600);
                    }, 10);
                }
            });
        });
    });
</script>
