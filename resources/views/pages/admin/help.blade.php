@extends('pages.admin.base')
@section('plugins-style')
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
    <link href={{ asset('assets/plugins/select2/css/select2.min.css') }} rel="stylesheet"> --}}
@endsection
@section('admin-content')
    <div class="row clearfix">
        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-12 flex-lg-column">
            <div class="sticky-lg-top">
                <div class="row row-deck">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-12 flex-column">
                        <div class="card mb-3 bg-info-light ">
                            <div class="card-body d-flex align-items-center justify-content-center flex-column">
                                <div class="preview-pane text-center">
                                    <svg width="100" fill="currentColor" class="bi bi-chat-text color-defult "
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z" />
                                        <path
                                            d="M4 5.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8zm0 2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z" />
                                    </svg>
                                    <a href="http://pixelwibes.com/" target="_blank"
                                        class="fw-bold fs-6 mt-2 d-flex justify-content-center color-defult ">Chat with
                                        us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-12 flex-column">
                        <div class="card mb-3 bg-lightyellow">
                            <div class="card-body d-flex align-items-center justify-content-center flex-column">
                                <div class="preview-pane text-center">
                                    <svg width="100" fill="currentColor" class="bi bi-envelope color-defult "
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z" />
                                    </svg>
                                    <a href="mailto:pixelwibes@gmail.com"
                                        class="fw-bold  fs-6 mt-2 d-flex justify-content-center color-defult ">Email us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-12">
            <div class="card border-0">
                <div class="card-header py-3">
                    <h4 class=" display-6 fw-bold">Help</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4 overflow-hidden">
                        <div class="bg-primary py-5 px-4 text-light">
                            <h4>pixelwibes.com</h4>
                            <span>Quick Start Guides Help Center</span>
                        </div>
                        <div class="p-4">
                            <p class="fw-bold">My-Task guide</p>
                            <span>Get started with My-Task Business and learn about features for admins and users.</span>
                            <div class="mt-4 mb-2">
                                <a href="http://pixelwibes.com/" class="btn btn-primary" target="_blank">Check out the
                                    guide</a>
                            </div>
                            <hr>
                            <p class="fw-bold">Get answers</p>
                            <span>Visit the help centre for answers to common issues.</span>
                            <div class="mt-4 mb-2">
                                <a href="http://pixelwibes.com/" class="btn btn-primary" target="_blank">Go to Help
                                    Centre</a>
                            </div>
                            <hr>
                            <span class="text-muted">Thanks for choosing <strong class="text-warning">Pixel Wibes</strong>
                                Admin.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Row End -->
@endsection
@push('plugins-js')
    {{-- <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
    <script src={{ asset('assets/plugins/select2/js/select2.min.js') }}></script> --}}
@endpush
@push('js')
    {{-- <script src={{ asset('app-js/transaction/history.js') }}></script> --}}
    {{-- <script src={{ asset('app-js/crud/post.js') }}></script> --}}
@endpush
