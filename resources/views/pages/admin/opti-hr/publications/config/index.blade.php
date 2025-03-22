@extends('pages.admin.base')

@section('admin-content')
    <div class="row g-0">
        <div class="col-12 d-flex">

            <!-- Card: -->
            <div class="card card-chat-body border-0  w-100 px-4 px-md-5 py-3 py-md-4">

                <!-- Chat: Header -->
                <div class="chat-header d-flex justify-content-between align-items-center border-bottom pb-3">
                    <div class="d-flex align-items-center">

                        <a href="javascript:void(0);" title="">
                            <img class="avatar rounded" src="{{ asset('assets/images/xs/avatar2.jpg') }}" alt="avatar">
                        </a>
                        <div class="ms-3">
                            <h2 class="mb-0">Espace Collaboratif</h2>
                            <small class="text-muted">Notes et informations</small>
                        </div>
                    </div>

                </div>

                <!-- Chat: body -->
                <ul class="chat-history list-unstyled mb-0 py-lg-5 py-md-4 py-3 flex-grow-1">
                    @forelse ($publications as $publication)
                        <li
                            class="parent mb-3 d-flex flex-row{{ $publication->author_id === auth()->user()->id ? '-reverse' : '' }} align-items-end {{ $publication->author_id !== auth()->user()->id && $publication->status === 'pending' ? 'd-none' : '' }}">
                            <div class="max-width-70">
                                <div class="user-info mb-1">
                                    <i class="icofont icofont-calendar fs-6    ">
                                    </i>
                                    <span class="text-muted small message-time">{{ $publication->published_at }}</span>
                                </div>
                                <div
                                    class="card border-0 p-3 {{ $publication->author_id === auth()->user()->id ? 'bg-primary text-light' : 'light-primary-bg' }}">
                                    <h6 class="model-value"> {{ $publication->title }}</h6>
                                    <div class=" text-wrap"> {{ $publication->content }}</div>

                                    @if ($publication->files->isNotEmpty())
                                        <div class="message">

                                            <p>Fichier joint:</p>
                                            @foreach ($publication->files as $file)
                                                <a href="#" class="outline-success my-1 me-2 downloadBtn"
                                                    data-publication-id="{{ $file->id }}">
                                                    <div
                                                        class="d-flex ms-3 align-items-center flex-fill  img-thumbnail pe-2">
                                                        <span
                                                            class="avatar lg light-danger-bg  text-center d-flex align-items-center justify-content-center">
                                                            @php
                                                                $mimeType = $file->mime_type; // Le type MIME récupéré depuis la base de données
                                                            @endphp

                                                            <i
                                                                class="fs-5 me-2
                                                        @if (strpos($mimeType, 'pdf') !== false) icofont-file-pdf text-danger
                                                        @elseif (strpos($mimeType, 'image') !== false)
                                                            icofont-image text-success
                                                        @else
                                                            icofont-file-alt text-warning @endif
                                                    "></i>

                                                        </span>
                                                        <div class="d-flex flex-column ps-3">
                                                            <h6 class="fw-bold mb-0 small-14">{{ $file->display_name }}</h6>
                                                        </div>


                                                    </div>
                                                </a>
                                            @endforeach


                                        </div>
                                    @endif
                                    <div
                                        class="d-flex flex-row-reverse {{ $publication->status === 'published' ? 'd-none' : '' }}">
                                        <a href="#" class=" my-1 me-2">
                                            <i class="icofont-ban fs-5 text-danger"></i>
                                            Non Publié
                                        </a>
                                    </div>

                                </div>
                            </div>
                            @can('configurer-une-publication')
                                <!-- More option -->
                                <div class="btn-group">
                                    <a href="#" class="nav-link py-2 px-3 text-muted" data-bs-toggle="dropdown"
                                        aria-expanded="false"><i class="icofont-navigation-menu"></i>
                                    </a>
                                    <ul class="dropdown-menu border-0 shadow">
                                        <li>
                                            <div class="modelUpdateFormContainer dropdown-item py-2 rounded"
                                                id="publicationUpdateForm{{ $publication->id }}">

                                                <form
                                                    data-model-update-url="{{ route('publications.config.updateStatus', [$publication->status === 'published' ? 'pending' : 'published', $publication->id]) }}">




                                                    <a role="button" class=" modelUpdateBtn " atl="update client status">
                                                        <span class="normal-status">
                                                            <i class="icofont-check text-success  "></i>
                                                            <span
                                                                class="d-none d-sm-none d-md-inline">{{ $publication->status === 'published' ? 'Cacher' : 'Publier' }}</span>
                                                        </span>
                                                        <span class="indicateur d-none">
                                                            <span class="spinner-grow spinner-grow-sm" role="status"
                                                                aria-hidden="true"></span>
                                                            Un Instant...
                                                        </span>
                                                    </a>

                                                </form>
                                            </div>

                                        </li>
                                        <li>

                                            <a class="dropdown-item py-2 rounded modelDeleteBtn" data-model-action="delete"
                                                data-model-delete-url={{ route('publications.config.destroy', $publication->id) }}
                                                data-model-parent-selector="li.parent" role="button">
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
                            @endcan



                        </li>

                    @empty
                    @endforelse
                </ul>
                @can('créer-une-publication')
                    <!-- Chat: Footer -->
                    <div class="chat-message">

                        @include('pages.admin.opti-hr.publications.config.create')
                    </div>
                @endcan


            </div>
        </div>
    </div> <!-- row end -->
    @include('pdf.overview.main')
@endsection
@push('js')
    <script src="{{ asset('app-js/publications/pdf.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
@endpush
