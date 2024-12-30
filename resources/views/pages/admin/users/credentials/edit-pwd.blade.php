   <!-- Add Department-->
   <div class="modal fade" id="credentialsPasswordUpdate{{ $user->id }}" tabindex="-1" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable modelUpdateFormContainer"
           id="credentialsPasswordForm{{ $user->id }}">
           <form data-model-update-url="{{ route('credentials.changePassword', $user->id) }}">
               {{-- @csrf --}}
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title  fw-bold" id="absenceTypeLabel">Modifier Le Mot De Passe
                       </h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                   </div>
                   <div class="modal-body">

                       <div class="mb-3">
                           <label for="password" class="form-label required">Nouveau Mot De Passe</label>
                           <input type="password" class="form-control" id="password" name="password" value="">
                       </div>
                       <div class="mb-3">
                           <label for="password_confirmation" class="form-label required">Confirmer Le Mot De
                               Passe</label>
                           <input type="password" class="form-control" id="password_confirmation"
                               name="password_confirmation" value="">
                       </div>

                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                       <button type="submit" class="btn btn-primary  modelUpdateBtn" atl="Modifier Absence Type"
                           data-bs-dismiss="modal">
                           <span class="normal-status">
                               Enregistrer
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
