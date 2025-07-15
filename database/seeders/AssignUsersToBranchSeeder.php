<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AssignUsersToBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ID de la branche à associer (ex: branche créée avec l'ID = 1)
        $branchId = 1;

        // Récupérer tous les utilisateurs
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            // Vérifie si l'utilisateur est déjà associé à cette branche
            $exists = DB::table('branch_user')
                ->where('user_id', $user->id)
                ->where('branch_id', $branchId)
                ->exists();

            if (! $exists) {
                DB::table('branch_user')->insert([
                    'user_id'    => $user->id,                    // L'ID de l'utilisateur
                    'branch_id'  => $branchId,                    // L'ID de la branche à associer
                    'is_default' => true,                         // Marque comme branche par défaut
                    'created_at' => Carbon::now(),                // Timestamp de création
                    'updated_at' => Carbon::now(),                // Timestamp de mise à jour
                ]);
            }
        }
    }
}
