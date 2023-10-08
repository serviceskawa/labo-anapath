<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\Operation;
use App\Models\Ressource;
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
        $key = 'Tables_in_' . env('DB_DATABASE') ;
        $tables = DB::select('SHOW TABLES');
        $ressources = [];
        $filler = 0;
        foreach ($tables as $table) {
           $ressources[$filler] = [
                'id' => $filler + 1,
                'titre' => $table->Tables_in_fzbnpjhy_caapgestion,
                'slug' => Str::slug($table->Tables_in_fzbnpjhy_caapgestion)
           ];
           $filler++;
        }
        DB::table('ressources')->insert($ressources);
        DB::table('permissions')->delete();
        $ope = ['view', 'create', 'edit', 'delete'];
        $permissions = [];
        $filler = 0;
        $res_index = 0;
        $k = 0;
        foreach ($ope as $value) {
            foreach ($tables as $table) {
                $op = Operation::find(($k + 1));
                $res = Ressource::all();
                foreach ($res as $re) {
                    if ($re->titre == $table->Tables_in_fzbnpjhy_caapgestion) {
                        $res_index = $re->id;
                        break;
                    }
                }
                $ress = Ressource::find($res_index);
                $permissions[$filler] = [
                    'id' => $filler + 1,
                    "titre" => $value.' '.$table->Tables_in_fzbnpjhy_caapgestion,
                    "slug" => Str::slug($value.' '.$table->Tables_in_fzbnpjhy_caapgestion),
                    "operation_id" => $op->id,
                    "ressource_id" => $ress->id
                ];
                $filler++;
            }
            $k++;
        }
        DB::table('permissions')->insert($permissions);
    }
}
