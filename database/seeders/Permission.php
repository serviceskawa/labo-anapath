<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Permission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('operations')->delete();
        $operations = [
            [
                'id' =>'1', 'operation'=>'view',
            ],[
                'id' =>'2', 'operation'=>'create',
            ],[
                'id' =>'3', 'operation'=>'edit',
            ],[
                'id' =>'4', 'operation'=>'delete',
            ]
        ];
        DB::table('operations')->insert($operations);
        
        
        DB::table('ressources')->delete();
        $ressources = [
            [
                'id' =>'1', 'titre'=>'dashboard', 'slug'=> Str::slug('dashboard'),
            ],[
                'id' =>'2', 'titre'=>'examens', 'slug'=> Str::slug('examens'),
            ],[
                'id' =>'3', 'titre'=>'examens categories', 'slug'=> Str::slug('examens categories'),
            ],[
                'id' =>'4', 'titre'=>'patients', 'slug'=> Str::slug('patients'),
            ],[
                'id' =>'5', 'titre'=>'hôpitaux', 'slug'=> Str::slug('hôpitaux'),
            ],[
                'id' =>'6', 'titre'=>'medecins traitants', 'slug'=> Str::slug('medecins traitants'),
            ],[
                'id' =>'7', 'titre'=>'contrats', 'slug'=> Str::slug('contrats'),
            ],[
                'id' =>'8', 'titre'=>'demandes examens', 'slug'=> Str::slug('demandes examens'),
            ],[
                'id' =>'9', 'titre'=>'compte rendu', 'slug'=> Str::slug('compte rendu'),
            ],[
                'id' =>'10', 'titre'=>'template compte rendu', 'slug'=> Str::slug('template compte rendu'),
            ],[
                'id' =>'11', 'titre'=>'paramètres systeme', 'slug'=> Str::slug('paramètres systeme'),
            ],[
                'id' =>'12', 'titre'=>'paramètres compte rendu', 'slug'=> Str::slug('paramètres compte rendu'),
            ],[
                'id' =>'13', 'titre'=>'users', 'slug'=> Str::slug('users'),
            ],[
                'id' =>'14', 'titre'=>'rôles', 'slug'=> Str::slug('rôles'),
            ],[
                'id' =>'15', 'titre'=>'permissions', 'slug'=> Str::slug('permissions'),
            ]
        ];
        DB::table('ressources')->insert($ressources);

        DB::table('permissions')->delete();

        $ope = ['view', 'create', 'edit', 'delete'];
        $ress = [
            Str::slug('dashboard'), Str::slug('examens'), Str::slug('examens categories'), Str::slug('patients'), 
            Str::slug('hôpitaux'),
            Str::slug('medecins traitants'), 
            Str::slug('contrats'), 
            Str::slug('demandes examens'), 
            Str::slug('compte rendu'), 
            Str::slug('template compte rendu'),
            Str::slug('paramètres systeme'),
            Str::slug('paramètres compte rendu'), Str::slug('users'), Str::slug('rôles'), Str::slug('permissions'),
        ];

        foreach ($ope as $key => $value) {

            foreach ($ress as $resKey => $resValue) {
				$permissions[] = [
					'titre' =>$value.' '.$resValue,
					'slug' =>Str::slug($value.'_'.$resValue),
					'operation_id' => $key+1,
					'ressource_id' => $resKey+1
				];

			}
        }
        DB::table('permissions')->insert($permissions);
    }
}
