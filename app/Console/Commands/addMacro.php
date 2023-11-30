<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\test_pathology_macro;
use App\Models\TestOrderAssignment;
use App\Models\TestOrderAssignmentDetail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class addMacro extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:macro';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Passe les etapes aux demandes déjà affectées';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = TestOrderAssignmentDetail::all();
        $employee = Employee::find(1);
            foreach ($orders as $key => $detail) {
                $macro = $macro = new test_pathology_macro();
                $macro->id_employee = $employee->id;
                $macro->date = Carbon::now();
                $macro->user_id = 5;
                $macro->id_test_pathology_order = $detail->test_order_id;
                $macro->circulation = 1;
                $macro->embedding = 1;
                $macro->microtomy_spreading = 1;
                $macro->staining = 1;
                $macro->save();
            }
        // Envoyer un message de succès
        $this->info('Ajout effectué avec succès!');
        return 0;
    }
}
