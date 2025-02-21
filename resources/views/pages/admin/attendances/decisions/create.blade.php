   <!-- Add Holiday-->
   <div class="modal fade" id="addDecisionModal" tabindex="-1" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
           <form id="modelAddForm" class="modal-content"
               data-model-add-url="{{ route('decisions.save', $decision ? $decision->id : null) }}">
               @csrf

               <div class="modal-header">
                   <h5 class="modal-title  fw-bold" id="absenceTypeLabel">Changer la décision courante</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">

                   <div class="mb-3">
                       <label for="date" class="form-label required">Date</label>
                       <input type="date" class="form-control" id="date" name="date"
                           value={{ $decision ? $decision->date : '' }}>
                   </div>
                   <div class="mb-3">
                       <label for="number" class="form-label required">Numéro</label>
                       <input type="text" class="form-control" id="number" name="number"
                           value={{ $decision ? $decision->number : '' }}>
                   </div>
                   <div class="mb-3">
                       <label for="year" class="form-label required">Année</label>
                       <input type="text" class="form-control" id="year" name="year"
                           value={{ $decision ? $decision->year : '' }}>
                   </div>
                   <div class="mb-3">
                       <label for="reference" class="form-label required">Référence</label>
                       <input type="text" class="form-control" id="reference" name="reference"
                           value={{ $decision ? $decision->reference : '' }}>
                   </div>

               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                   <button type="submit" class="btn btn-primary" atl="Ajouter Absence Type" id="modelAddBtn"
                       data-bs-dismiss="modal">
                       <span class="normal-status">
                           Ajouter
                       </span>
                       <span class="indicateur d-none">
                           <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                           Un Instant...
                       </span>
                   </button>

               </div>
           </form>
       </div>
   </div>
