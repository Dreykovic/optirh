{{-- Widget: Demandes de documents recentes --}}
<div class="card dashboard-card h-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="icofont-file-document text-success"></i>
            Demandes de documents
        </h5>
        <span class="badge bg-success">{{ $recentDocuments->count() ?? 0 }}</span>
    </div>
    <div class="card-body p-0">
        @if(isset($recentDocuments) && $recentDocuments->count() > 0)
            <div class="table-responsive">
                <table class="table dashboard-table mb-0">
                    <thead>
                        <tr>
                            <th>Employe</th>
                            <th>Type de document</th>
                            <th>Date demande</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDocuments as $document)
                            @php
                                $employee = $document->duty->employee ?? null;
                            @endphp
                            @if($employee)
                                <tr>
                                    <td>
                                        <div class="employee-info">
                                            <x-employee-icon :employee="$employee" />
                                            <div>
                                                <div class="name">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $document->document_type->label ?? 'N/A' }}</td>
                                    <td>
                                        <small>{{ $document->date_of_application ? \Carbon\Carbon::parse($document->date_of_application)->format('d/m/Y') : 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @switch($document->stage)
                                            @case('PENDING')
                                                <span class="badge-status pending">En attente</span>
                                                @break
                                            @case('APPROVED')
                                                <span class="badge-status approved">Approuve</span>
                                                @break
                                            @case('REJECTED')
                                                <span class="badge-status rejected">Rejete</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $document->stage }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="icofont-file-document d-block"></i>
                <div class="message">Aucune demande de document recente</div>
            </div>
        @endif
    </div>
    <div class="card-footer">
        <a href="{{ route('documents.requests') }}" class="btn btn-sm btn-success">
            Voir toutes les demandes
        </a>
    </div>
</div>
