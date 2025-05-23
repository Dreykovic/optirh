   <!-- Add Department-->
   <div class="modal fade" id="documentTypeAdd" tabindex="-1" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
           <form id="modelAddForm"class="modal-content" data-model-add-url="{{ route('documentTypes.save') }}">
               @csrf

               <div class="modal-header">
                   <h5 class="modal-title  fw-bold" id="documentTypeLabel">Ajout Type Document</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">

                   <div class="mb-3">
                       <label for="libelle" class="form-label required">Libellé</label>
                       <input type="text" class="form-control" id="libelle" name="libelle">
                   </div>
                   <div class="mb-3">
                       <label for="description" class="form-label">Description</label>
                       <textarea name="description" class="form-control" id="description" cols="30" rows="3"></textarea>
                   </div>


               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                   <button type="submit" class="btn btn-primary" atl="Ajouter Document Type" id="modelAddBtn"
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
