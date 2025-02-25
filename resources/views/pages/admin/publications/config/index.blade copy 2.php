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
                            class="mb-3 d-flex flex-row{{ $publication->author_id === auth()->user()->id ? '-reverse' : '' }} align-items-end">
                            <div class="max-width-70">
                                <div class="user-info mb-1">
                                    <img class="avatar sm rounded-circle me-1 {{ $publication->author_id === auth()->user()->id ? 'd-none' : '' }}"
                                        src="{{ asset('assets/images/xs/avatar2.jpg') }}" alt="avatar">
                                    <span class="text-muted small">10:10 AM, Today</span>
                                </div>
                                <div
                                    class="card border-0 p-3 {{ $publication->author_id === auth()->user()->id ? 'bg-primary text-light' : '' }}">
                                    <h6> {{ $publication->title }}</h6>
                                    <div class="message"> {{ $publication->content }}</div>
                                    @if ($publication->file)
                                        <div class="message">
                                            <p>Please find attached images</p>
                                            {{-- <img class="w120 img-thumbnail" src="{{ asset('assets/images/gallery/1.jpg') }}"
                                                alt="" /> --}}
                                            <a href="#" class="outline-success my-1 me-2 downloadBtn"
                                                data-publication-id="{{ $publication->id }}">
                                                <div class="d-flex ms-3 align-items-center flex-fill  img-thumbnail">
                                                    <span
                                                        class="avatar lg bg-lightgreen  text-center d-flex align-items-center justify-content-center">
                                                        <i class="icofont-file-pdf fs-5"></i>
                                                    </span>
                                                    <div class="d-flex flex-column ps-3">
                                                        <h6 class="fw-bold mb-0 small-14">file1.pdf</h6>
                                                    </div>


                                                </div>
                                            </a>


                                        </div>
                                    @endif

                                </div>
                            </div>
                            <!-- More option -->
                            <div class="btn-group">
                                <a href="#" class="nav-link py-2 px-3 text-muted" data-bs-toggle="dropdown"
                                    aria-expanded="false"><i class="icofont-navigation-menu"></i>
                                </a>
                                <ul class="dropdown-menu border-0 shadow">
                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                    <li><a class="dropdown-item" href="#">Share</a></li>
                                    <li><a class="dropdown-item" href="#">Delete</a></li>
                                </ul>
                            </div>
                        </li>

                    @empty
                    @endforelse
                </ul>
                {{-- <div>
                    <!-- Chat: right -->
                    <li class="mb-3 d-flex flex-row-reverse align-items-end">
                        <div class="max-width-70 text-right">
                            <div class="user-info mb-1">
                                <span class="text-muted small">10:12 AM, Today</span>
                            </div>
                            <div class="card border-0 p-3 bg-primary text-light">
                                <div class="message">How many task are working?</div>
                            </div>
                        </div>
                        <!-- More option -->
                        <div class="btn-group">
                            <a href="#" class="nav-link py-2 px-3 text-muted" data-bs-toggle="dropdown"
                                aria-expanded="false"><i class="icofont-navigation-menu"></i>
                            </a>
                            <ul class="dropdown-menu border-0 shadow">
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item" href="#">Share</a></li>
                                <li><a class="dropdown-item" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Chat: left -->
                    <li class="mb-3 d-flex flex-row align-items-end">
                        <div class="max-width-70">
                            <div class="user-info mb-1">
                                <img class="avatar sm rounded-circle me-1" src="{{ asset('assets/images/xs/avatar2.jpg') }}"
                                    alt="avatar">
                                <span class="text-muted small">10:10 AM, Today</span>
                            </div>
                            <div class="card border-0 p-3">
                                <div class="message">
                                    <p>Please find attached images</p>
                                    <img class="w120 img-thumbnail" src="{{ asset('assets/images/gallery/1.jpg') }}"
                                        alt="" />
                                    <img class="w120 img-thumbnail" src="{{ asset('assets/images/gallery/2.jpg') }}"
                                        alt="" />
                                </div>
                            </div>
                        </div>
                        <!-- More option -->
                        <div class="btn-group">
                            <a href="#" class="nav-link py-2 px-3 text-muted" data-bs-toggle="dropdown"
                                aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <ul class="dropdown-menu border-0 shadow">
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item" href="#">Share</a></li>
                                <li><a class="dropdown-item" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </li>
                </div> --}}

                <!-- Chat: Footer -->
                <div class="chat-message">
                    <textarea class="form-control" placeholder="Enter text here..."></textarea>
                </div>

            </div>
        </div>
    </div> <!-- row end -->
@endsection
@push('js')
    <script src="{{ asset('app-js/publications/pdf.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
@endpush
