@extends('pages.admin.base')

@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div
                class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">Décision Courante</h3>
                <div class="col-auto d-flex w-sm-100">
                    <button type="button" class="btn btn-dark btn-set-task w-sm-100" data-bs-toggle="modal"
                        data-bs-target="#addDecisionModal"><i class="icofont-plus-circle me-2 fs-6"></i>Changer</button>
                </div>
            </div>
        </div>
    </div> <!-- Row end  -->
    @if ($decision)
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
                                            <h2>N° {{ $decision->number }}</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center py-2">
                                            <h4 class="mb-0">
                                                {{ "{$decision->number}/{$decision->year}/{$decision->reference}" }}</h4>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="pt-2 pb-2 text-center">
                                            <a href="#">Du</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-0 text-center">
                                            @formatDateOnly($decision->date)
                                        </td>
                                    </tr>
                                </table>

                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div> <!-- Row end  -->
    @else
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12">
                <div class="d-flex justify-content-center">
                    <div class="col-auto d-flex w-sm-100">
                        <h3>Pas De décision enregistré.</h3>
                    </div>
                </div>
            </div>
        </div> <!-- Row end  -->
    @endif


    @include('pages.admin.attendances.annual-decisions.create')
@endsection

@push('js')
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
@endpush
