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
                            <option value="{{$dept->id}}">{{ strtoupper($dept->name) }}</option>
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
            <form action="{{ route('files.invoices') }}" method='POST' enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <h4 for="files">Envoi des bulletins</h4>
                </div>
                {{$details ?? ''}}
                    <input type="file" name="file" id="files" class='form-control mb-3' accept=".pdf">
                    <!-- <input type="file" name="files[]" id="files" class='form-control mb-3' accept=".pdf" multiple> -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-lg btn-block lift text-uppercase btn-primary" atl="Envoyer factures">
                            <span class="normal-status">
                                Envoyer
                            </span>
                            <span class="indicateur d-none">
                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                Un Instant...
                            </span>
                        </button>  
                    </div>
               </form>
               
            </div>
        </div>
        <!--  -->
   
    {{-- Résumé du traitement --}}
    @if (session('summary') && session('details'))
        <div class="mt-5">
            <h5>Résumé d'envoi</h5>

            {{-- Résumé général --}}
            <div class="alert alert-info">
                <p><strong>Envois Réussis :</strong> {{ session('summary')['successful'] }}</p>
                <p><strong>Envois Non Reconnus :</strong> {{ session('summary')['failed'] }}</p>
                <p><strong>Non Envoyés :</strong> {{ session('summary')['missing'] }}</p>
            </div>

        <!-- reussi -->

            {{-- Détails des échecs --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Envois Non Reconnus</h5>
                    @if (!empty(session('details')['failed']))
                        <ul class="list-group">
                            @foreach (session('details')['failed'] as $file)
                                <li class="list-group-item list-group-item-danger">
                                    Fichier : <strong>{{ $file }}</strong>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Aucun fichier échoué.</p>
                    @endif
                </div>
            </div>

            {{-- Détails des codes manquants --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Non Envoyés</h5>
                    @if (!empty(session('details')['missing']))
                        <ul class="list-group">
                            @foreach (session('details')['missing'] as $code)
                                <li class="list-group-item list-group-item-warning">
                                    Employé : <strong>{{ $code }}</strong>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Tous les employés ont reçu un fichier.</p>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
        <!--  -->
    </div>

</div>
@endsection
@push('plugins-js')

@endpush
@push('js')
<script src="{{ asset('app-js/personnel/paginator.js') }}"></script>
<!-- <script src="{{ asset('app-js/crud/post.js') }}"></script> -->
<script src="{{ asset('app-js/personnel/membres/pay-list.js') }}"></script>



@endpush