    <div class="row mb-4">
        <div class="col-sm-6">
            <h6 class="mb-3">Supérieur Hiérarchique:</h6>
            <div>Période</div>


        </div>

        <div class="col-sm-6">
            <h6 class="mb-3">

                {{ $documentRequest->duty->job->n_plus_one_job ? $documentRequest->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->last_name . ' ' . $documentRequest->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->first_name . ' (' . $documentRequest->duty->job->n_plus_one_job->title . ')' : 'Néant' }}
            </h6>
            <div> Du <strong> @formatDateOnly($documentRequest->start_date)</strong> Au
                <strong> @formatDateOnly($documentRequest->end_date)
                </strong>
            </div>

        </div>
    </div> <!-- Row end  -->

    @if ($documentRequest->proof)
        <div class="mb-3 ">
            <div class="py-2 d-flex align-items-center">
                <div class="d-flex ms-3 align-items-center flex-fill">
                    <span
                        class="avatar lg bg-lightgreen rounded-circle text-center d-flex align-items-center justify-content-center"><i
                            class="icofont-file-pdf fs-5"></i></span>
                    <div class="d-flex flex-column ps-3">
                        <h6 class="fw-bold mb-0 small-14">file1.pdf</h6>
                    </div>
                </div>
                <button type="button" class="btn bg-lightgreen text-end">Download</button>
            </div>

        </div>
    @endif

    <div class=" pb-3  ">

        <span class="float-start"> <strong>Commentaire : </strong>
        </span>
        <p class="ml-1">{{ $documentRequest->comment ? ' ' . $documentRequest->comment : ' Aucun' }}</p>
    </div>
