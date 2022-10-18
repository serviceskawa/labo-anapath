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
