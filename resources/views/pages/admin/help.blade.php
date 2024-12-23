@extends('pages.admin.base')
@section('plugins-style')
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
    <link href={{ asset('assets/plugins/select2/css/select2.min.css') }} rel="stylesheet"> --}}
@endsection
@section('admin-content')
    <div class="row clearfix g-3">
        <div class="col">
            <x-no-data color="info" text="Bientôt Disponible" />
        </div>
    </div>
@endsection
@push('plugins-js')
    {{-- <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
    <script src={{ asset('assets/plugins/select2/js/select2.min.js') }}></script> --}}
@endpush
@push('js')
    {{-- <script src={{ asset('app-js/transaction/history.js') }}></script> --}}
    {{-- <script src={{ asset('app-js/crud/post.js') }}></script> --}}
@endpush
