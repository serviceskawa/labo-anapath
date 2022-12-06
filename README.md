## Etapes

# Composer install

# Composer dump-autoload

# php artisan key:generate

# php artisan migrate

# php artisan db:seed pour tout les seeders ou php artisan db:seed --class=DbSeeder pour un seeder specific

# /login mdp: P@ssw0rd et email: admin@admin.com

# faire les configurations dans settings

### A mettre dans chaque fonction du controller pour activer la fonctionnalité rôle et permission

# php artisan db:seed

# view-roles est le slug disponible dans la table permissions

if (!getOnlineUser()->can('view-roles')) {
return back()->with('error', "Vous n'êtes pas autorisé");
}

### Correction de 05/12/22 après deploiement

## hospitals

Sauf le champs name est obligatoire

                    <tbody>

                        @foreach ($examens as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            {{-- <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td> --}}
                            <td>{{ $item->code }} </td>
                            <td>{{ $item->patient->firstname }} {{ $item->patient->lastname }}</td>
                            <td>{{ $item->getDoctor()->name }}</td>
                            <td>{{ $item->getHospital()->name }}</td>
                            <td>{{ $item->total }}</td>
                            <td>
                                <a type="button" href="{{ route('details_test_order.index', $item->id) }}"
                                    class="btn btn-primary"><i class="mdi mdi-eye"></i> </a>
                                @if ($item->status != 1)
                                <button type="button" onclick="deleteModal({{ $item->id }})" class="btn btn-danger"><i
                                        class="mdi mdi-trash-can-outline"></i>
                                </button>
                                @endif

                            </td>

                        </tr>
                        @endforeach




                    </tbody>
