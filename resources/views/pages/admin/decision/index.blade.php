@extends('pages.admin.base')
@section('plugins-style')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}>
@endsection
@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div
                class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">Décision Courante</h3>
                <div class="col-auto d-flex w-sm-100">
                    <button type="button" class="btn btn-dark btn-set-task w-sm-100" data-bs-toggle="modal"
                        data-bs-target="#addHolidayModal"><i class="icofont-plus-circle me-2 fs-6"></i>Changer</button>
                </div>
            </div>
        </div>
    </div> <!-- Row end  -->

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-12">
            <div class="d-flex justify-content-center">
                <table class="card p-5">
                    <tr>
                        <td></td>
                        <td class="text-center">
                            <table>
                                <tr>
                                    <td class="text-center">
                                        <h2>$48.98 Paid</h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center py-2">
                                        <h4 class="mb-0">Thanks for using PXL Inc.</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pt-2 pb-4">
                                        <table>
                                            <tr>
                                                <td>
                                                    Attn: <strong>Ryan MacLeod</strong> Winston Salem FL 27107<br>
                                                    Email: RyanmacLeod@gmail.com<br>
                                                    Phone: +88 123 456 789<br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pt-2">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td class="text-start">Extended License</td>
                                                            <td class="text-end">$ 20.99</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-start">1 year subcription</td>
                                                            <td class="text-end">$ 19.99</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-start">Instalation and Customization</td>
                                                            <td class="text-end">$ 8.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-start w-80"><strong>Total</strong></td>
                                                            <td class="text-end fw-bold">$ 48.98</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pt-2 pb-2 text-center">
                                        <a href="#">View in browser</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0 text-center">
                                        PXL Inc. 47 Aurora St. South West, CT 06074
                                    </td>
                                </tr>
                            </table>
                            <table class="mt-3 text-center w-100">
                                <tr>
                                    <td class="aligncenter content-block">Questions? Email <a
                                            href="mailto:">info@pixelwibes.com</a></td>
                                </tr>
                            </table>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div> <!-- Row end  -->

    @include('pages.admin.attendances.holidays.create')
@endsection
@push('plugins-js')
    <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
@endpush
@push('js')
    <script src="{{ asset('app-js/attendances/holidays/table.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
@endpush
