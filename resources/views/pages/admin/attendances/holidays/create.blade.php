   <!-- Add Holiday-->
   <div class="modal fade" id="addHolidayModal" tabindex="-1" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
           <form id="modelAddForm" data-model-add-url="{{ route('holidays.save') }}">
               @csrf
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title  fw-bold" id="absenceTypeLabel">Ajout Jour Férié</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                   </div>
                   <div class="modal-body">

                       <div class="mb-3">
                           <label for="date" class="form-label required">Date</label>
                           <input type="date" class="form-control" id="date" name="date">
                       </div>
                       <div class="mb-3">
                           <label for="name" class="form-label required">Nom Du Jour</label>
                           <input type="text" class="form-control" id="name" name="name">
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
               </div>
           </form>
       </div>
   </div>
