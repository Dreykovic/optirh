@extends('pages.admin.base')

@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div
                class="card-header p-0 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold py-3 mb-0">Publications</h3>
                <div class="d-flex py-2 project-tab flex-wrap w-sm-100">
                    <button type="button" class="btn btn-dark btn-set-task w-sm-100" data-bs-toggle="modal"
                        data-bs-target="#publicationAddModal"><i class="icofont-plus-circle me-2 fs-6"></i>Ajouter</button>
                    <ul class="nav nav-tabs tab-body-header rounded ms-3 prtab-set w-sm-100" role="tablist">
                        <li class="nav-item"><a class="nav-link {{ $status === 'all' ? 'active' : '' }}"
                                href="{{ route('publications.config.index', 'all') }}" role="tab">Toutes</a></li>

                        <li class="nav-item"><a class="nav-link {{ $status === 'published' ? 'active' : '' }}"
                                href="{{ route('publications.config.index', 'published') }}" role="tab">Publiés</a></li>
                        <li class="nav-item"><a class="nav-link {{ $status === 'pending' ? 'active' : '' }}"
                                href="{{ route('publications.config.index', 'pending') }}" role="tab">À venir</a></li>


                    </ul>
                </div>
            </div>
        </div>
    </div> <!-- Row end  -->

    <div class="row g-3 gy-5 py-3 row-deck align-items-center">
        @forelse ($publications as $publication)
            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="card parent">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mt-5">
                            <div class="lesson_name">
                                <div class="project-block light-success-bg">
                                    <i class="icofont-tick-boxed"></i>
                                </div>
                                <span class="model-value  project_name fw-bold"> {{ $publication->title }}</span>

                            </div>

                        </div>
                        <div class="d-flex flex-row-reverse">
                            <div type="div" class="btn   dropdown-toggle-split" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <span class="fw-bolder">...</span>
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </div>
                            <ul class="dropdown-menu border-0 shadow py-3 px-2">
                                <li>
                                    <a class="dropdown-item py-2 rounded" data-bs-toggle="modal"
                                        data-bs-target="#publicationDetailsUpdate{{ $publication->id }}" role="button">
                                        <i class="icofont-pen text-success"></i>

                                        <span class="d-none d-sm-none d-md-inline">Voir</span>
                                    </a>

                                </li>



                                <li>
                                    <a class="dropdown-item py-2 rounded modelDeleteBtn" data-model-action="delete"
                                        data-model-delete-url={{ route('publications.config.destroy', $publication->id) }}
                                        data-model-parent-selector="div.parent" role="button">
                                        <span class="normal-status">
                                            <i class="icofont-ui-delete text-danger"></i>

                                            <span class="d-none d-sm-none d-md-inline">Supprimer</span>

                                        </span>

                                        <span class="indicateur d-none">
                                            <span class="spinner-grow spinner-grow-sm" role="status"
                                                aria-hidden="true"></span>

                                        </span>
                                    </a>

                                </li>
                            </ul>
                        </div>

                        <div class="dividers-block"></div>

                        <div class="customer-like mb-2">



                            {{ Str::limit($publication->content, 250) }}





                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">

                            <div class='d-flex align-items-center py-2'>
                                <span class='bullet bg-primary me-3'></span>
                                <em>Plus de détails...</em>
                            </div>

                            <a href="#" class="btn btn-outline-success my-1 me-2 downloadBtn"
                                data-publication-id="{{ $publication->id }}"> <span class="  p-1 rounded">
                                    Aperçu</span> </a>

                        </div>

                    </div>
                </div>
            </div>
        @empty
            <x-no-data color="warning" text="Aucune Publication enregistrée" />
        @endforelse
        @include('pages.admin.publications.config.create')
        @include('pdf.overview.main')
    </div>
@endsection
@push('js')
    <script src="{{ asset('app-js/publications/pdf.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
@endpush
