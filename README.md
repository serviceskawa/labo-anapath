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

### Sauvegarde d'ajout de detail de demande d'examen

# type order deploiement

php artisan migrate --path=/database/migrations/2022_12_22_051410_create_type_orders_table.php

php artisan db:seed --class=TypeOrder

php artisan migrate --path=/database/migrations/2022_12_22_053519_add_type_id_to_table_test_orders.php
php artisan migrate --path=/database/migrations/2022_12_22_201639_create_data_codes_table.php

# Onco deployement

php artisan migrate
php artisan db:seed --class=TypeConsultationFileSeeder

# 23/01/2023

Creer Cytologie dans TypeOrder

# 27/01/2023

Rendre le type_consultation_id nullable

# 30/01/2023

ck_options dans compte rendu

https://ckeditor.com/docs/ckeditor5/latest/installation/getting-started/quick-start.html

# 31/01/23

Ajout du champs attribuate_doctor_id à test_order pour l'attribution du docteur signataire

demande d'examen, ajout de la fonction filter pour le tableau de index2; retrait des titres de values et ajout des ids;

php artisan db:seed --class=CategoryPrestationSeeder

# 03/02/2023

Ajout de prestation_id dans la table consultations
