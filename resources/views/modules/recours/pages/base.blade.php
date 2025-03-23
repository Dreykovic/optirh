@extends('base')
@section('content')
    @auth

        <!-- sidebar -->
        @include('modules.recours.partials.sidebar.index')

        <!-- main body area -->
        <div class="main px-lg-4 px-md-4">

            <!-- Body: Header -->
            @include('modules.recours.partials.header.index')

            <!-- Body: Body -->
            <div class="body d-flex py-3">
                <x-session-message />

                <div class="container-xxl">
                    @yield('admin-content')
                </div>
            </div>


        </div>
    @endauth
@endsection
@push('plugins-js')
    <script src="{{ asset('assets/js/template.js') }}"></script>
@endpush
