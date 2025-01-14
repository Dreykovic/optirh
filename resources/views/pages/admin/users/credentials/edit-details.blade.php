   <!-- Add Department-->
   <div class="modal fade" id="credentialsDetailsUpdate{{ $user->id }}" tabindex="-1" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable modelUpdateFormContainer"
           id="credentialsUpdateForm{{ $user->id }}">
           <form data-model-update-url="{{ route('credentials.updateDetails', $user->id) }}">
               {{-- @csrf --}}
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title  fw-bold" id="absenceTypeLabel">Modifier Les Informations De L'utilisateur
                       </h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                   </div>
                   <div class="modal-body">

                       <div class="mb-3">
                           <label for="email" class="form-label required">Email</label>
                           <input type="text" class="form-control" id="email" name="email"
                               value="{{ $user->email }}">
                       </div>
                       <div class="mb-3">
                           <label for="username" class="form-label required">Nom D'Utilisateur</label>
                           <input type="text" class="form-control" id="username" name="username"
                               value="{{ $user->username }}">
                       </div>
                       <div class="form-group">
                           <label class="form-label required">Statut</label>
                           <br>
                           <label class="fancy-radio">
                               <input type="radio" name="status" value="ACTIVATED"
                                   {{ $user->status === 'ACTIVATED' ? 'checked' : '' }}>
                               <span><i></i>Activé</span>
                           </label>
                           <label class="fancy-radio">
                               <input type="radio" name="status" value="DEACTIVATED"
                                   {{ $user->status === 'DEACTIVATED' ? 'checked' : '' }}>
                               <span><i></i>Désactivé</span>
                           </label>

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
