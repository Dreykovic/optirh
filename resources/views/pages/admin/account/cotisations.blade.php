@extends('pages.admin.base')
@section('plugins-style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
    <link href={{ asset('assets/plugins/select2/css/select2.min.css') }} rel="stylesheet">
@endsection
@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div
                class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold mb-0">Attendance (Admin)</h3>
                <div class="col-auto d-flex mt-1 mt-sm-0">
                    <button type="button" class="btn btn-dark  w-sm-100 me-2" data-bs-toggle="modal"
                        data-bs-target="#editattendance"><i class="icofont-edit me-2 fs-6"></i>Edit Attendance</button>
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Filter
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                        <li><a class="dropdown-item" href="#">All</a></li>
                        <li><a class="dropdown-item" href="#">Today Absent</a></li>
                        <li><a class="dropdown-item" href="#">Week Absent</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div> <!-- Row end  -->
    <div class="row clearfix g-3">
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="atted-info d-flex mb-3 flex-wrap">
                        <div class="full-present me-2">
                            <i class="icofont-check-circled text-success me-1"></i>
                            <span>Full Day Present</span>
                        </div>
                        <div class="Half-day me-2">
                            <i class="icofont-wall-clock text-warning me-1"></i>
                            <span>Half Day Present</span>
                        </div>
                        <div class="absent me-2">
                            <i class="icofont-close-circled text-danger me-1"></i>
                            <span>Full Day Absence</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th>1</th>
                                    <th>2</th>
                                    <th>3</th>
                                    <th>4</th>
                                    <th>5</th>
                                    <th>6</th>
                                    <th>7</th>
                                    <th>8</th>
                                    <th>9</th>
                                    <th>10</th>
                                    <th>11</th>
                                    <th>12</th>
                                    <th>13</th>
                                    <th>14</th>
                                    <th>15</th>
                                    <th>16</th>
                                    <th>17</th>
                                    <th>18</th>
                                    <th>19</th>
                                    <th>20</th>
                                    <th>21</th>
                                    <th>22</th>
                                    <th>23</th>
                                    <th>24</th>
                                    <th>25</th>
                                    <th>27</th>
                                    <th>28</th>
                                    <th>29</th>
                                    <th>30</th>
                                    <th>31</th>
                                    <th>Max</th>
                                    <th>Total</th>
                                    <th><i class="icofont-gear fs-5"></i></th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>

                                    <td>
                                        <i class="icofont-close-circled text-danger"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-close-circled text-danger"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-wall-clock text-warning"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-close-circled text-danger"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-wall-clock text-warning"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <i class="icofont-check-circled text-success"></i>
                                    </td>
                                    <td>
                                        <button type="button" class="btn light-success-bg">56</button>
                                    </td>
                                    <td>
                                        <span class="fw-bold small">Ryan Randall</span>
                                    </td>

                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                                            <button type="button" class="btn btn-outline-secondary"><i
                                                    class="icofont-edit text-success"></i></button>
                                            <button type="button" class="btn btn-outline-secondary deleterow"><i
                                                    class="icofont-ui-delete text-danger"></i></button>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Row End -->

    <!-- Edit Attendance-->
    <div class="modal fade" id="editattendance" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title  fw-bold" id="editattendanceLabel"> Edit Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Person</label>
                        <select class="form-select">
                            <option selected>Joan Dyer</option>
                            <option value="1">Ryan Randall</option>
                            <option value="2">Phil Glover</option>
                            <option value="3">Victor Rampling</option>
                            <option value="4">Sally Graham</option>
                            <option value="5">Robert Anderson</option>
                            <option value="6">Ryan Stewart</option>
                        </select>
                    </div>
                    <div class="deadline-form">
                        <form>
                            <div class="row g-3 mb-3">
                                <div class="col-sm-12">
                                    <label for="datepickerdedass" class="form-label">Select Date</label>
                                    <input type="date" class="form-control" id="datepickerdedass">
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Attendance Type</label>
                                    <select class="form-select">
                                        <option selected>Full Day Present</option>
                                        <option value="1">Half Day Present</option>
                                        <option value="2">Full Day Absence</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea78d" class="form-label">Edit Reason</label>
                        <textarea class="form-control" id="exampleFormControlTextarea78d" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Done</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('plugins-js')
    <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
    <script src={{ asset('assets/plugins/select2/js/select2.min.js') }}></script>
@endpush
@push('js')
    <script src="{{ asset('app-js/account/table.js') }}"></script>
    <script src={{ asset('app-js/crud/post.js') }}></script>
@endpush
