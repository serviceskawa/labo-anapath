<div class="modal fade" id="bs-example-modal-lg-edit-{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Informations personnelles</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employee.update', $item->id) }}" method="POST" autocomplete="on"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Nom<span style="color:red;">*</span></label>
                                <input type="text"
                                    value="{{ old('first_name') ? old('first_name') : $item->first_name }}"
                                    name="first_name" class="form-control" required>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Prénoms<span
                                        style="color:red;">*</span></label>
                                <input type="text" value="{{ old('last_name') ? old('last_name') : $item->last_name }}"
                                    name="last_name" class="form-control" required>
                            </div>
                        </div>


                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Email</label>
                                <input type="email" value="{{ old('email') ? old('email') : $item->email }}"
                                    name="email" class="form-control" >
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Téléphone</label>
                                <input type="text" value="{{ old('telephone') ? old('telephone') : $item->telephone }}"
                                    name="telephone" class="form-control" >
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Date de naissance</label>
                                <input type="date"
                                    value="{{ old('date_of_birth') ? old('date_of_birth') : $item->date_of_birth }}"
                                    name="date_of_birth" class="form-control" >
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Lieu de naissance</label>
                                <input type="text"
                                    value="{{ old('place_of_birth') ? old('place_of_birth') : $item->place_of_birth }}"
                                    name="place_of_birth" class="form-control"  />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Sexe</label>
                                <select class="form-select mb-3" name="gender" >
                                    <option>Selectionner le sexe</option>
                                    <option value="1" {{$item->gender="masculin" ? 'selected' : ''}}>Masculin
                                    </option>
                                    <option value="0" {{$item->gender="feminin" ? 'selected' : ''}}>Feminin
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Nationalité</label>
                                <input type="text"
                                    value="{{ old('nationality') ? old('nationality') : $item->nationality }}"
                                    name="nationality" class="form-control"  />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Adress</label>
                                <input type="text" name="address"
                                    value="{{ old('address') ? old('address') : $item->address }}" class="form-control"
                                     />
                            </div>

                            <div class="mb-3 col-lg-6">
                                <label for="simpleinput" class="form-label">Ville</label>
                                <input type="text" name="city" value="{{ old('city') ? old('city') : $item->city }}"
                                    class="form-control"  />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Numéro CNSS<span
                                        style="color:red;">*</span></label>
                                <input type="text"
                                    value="{{ old('cnss_number') ? old('cnss_number') : $item->cnss_number }}"
                                    name="cnss_number" class="form-control"  />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Utilisateur<span
                                        style="color:red;">*</span></label>
                                <select class="form-select select2" id="user_id" name="user_id">
                                    <option value="">Associer à un utilisateur</option>
                                    @forelse (getAllUsers() as $user)
                                        <option value="{{ $user->id }}" {{$user->id == $item->user_id}} >{{ $user->fullname() }}</option>
                                    @empty
                                        Ajouter un utilisateur
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Photo de l'employé</label>
                                <input type="file" name="photo_url" class="form-control" />
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
