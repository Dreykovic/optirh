   <!-- Add Department-->
   <div class="modal fade" id="credentialsRoleUpdate{{ $user->id }}" tabindex="-1" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable modelUpdateFormContainer"
           id="credentialsRoleForm{{ $user->id }}">
           <form data-model-update-url="{{ route('credentials.updateRole', $user->id) }}">
               {{-- @csrf --}}
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title  fw-bold" id="absenceTypeLabel">Modifier Le Role
                       </h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                   </div>
                   <div class="modal-body">
                       <div class="d-flex flex-stack flex-grow-1 ">
                           <!--begin::Content-->
                           <div class=" fw-semibold light-info-bg p-2">

                               <div class="fs-6 text-gray-700 ">Veuillez noter que si le niveau de rôle d'un
                                   utilisateur est réduit, cet utilisateur perdra tous les privilèges qui lui étaient
                                   attribués dans le rôle précédent.
                               </div>
                           </div>
                           <!--end::Content-->

                       </div>
                       <div class="dividers-block"></div>

                       <div class="mb-3">
                           <label class="fs-6 fw-semibold form-label ">
                               <span class="required">Changer le role de l'utilisateur</span>
                           </label>
                           <div class="customer-like mb-2">

                               <ul class="list-group mt-3">

                                   @foreach ($roles as $index => $role)
                                       <li class="list-group-item d-flex">
                                           <div class="border-end pe-2 ">
                                               <input class="form-check-input me-3" name="role" type="radio"
                                                   value="{{ $role->name }}"
                                                   id="kt_modal_update_role_option_{{ $role->id }}"
                                                   @foreach ($user->roles as $userRole)
                                                                     @if ($userRole->id == $role->id)
                                                                         checked
                                                     @endif @endforeach>
                                           </div>
                                           <div class="cs-text flex-fill ps-2">
                                               <span>
                                                   <label class="form-check-label"
                                                       for="kt_modal_update_role_option_{{ $role->id }}">
                                                       <div class="fw-bold text-gray-800">{{ $role->name }}
                                                       </div>

                                                   </label>
                                               </span>
                                           </div>
                                           <div class="vote-text">
                                               <span class="text-muted">{{ $role->permissions->count() }}
                                                   Permissions</span>
                                           </div>
                                       </li>
                                   @endforeach


                               </ul>

                           </div>



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
