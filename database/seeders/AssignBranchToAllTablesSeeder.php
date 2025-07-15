<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class AssignBranchToAllTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer une branche (si elle n'existe pas déjà)
        $branchId = DB::table('branches')->insertGetId([
            'name' => 'Siège Central',
            'code' => 'SC001',
            'location' => 'Cotonou',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        
        $tables = [
            'appel_by_reports',
            'appel_test_oders',
            'appointments',
            'articles',
            'assignment_doctors',
            'banks',
            // 'branches',
            'branch_user',
            'cashboxes',
            'cashbox_adds',
            'cashbox_dailies',
            'cashbox_tickets',
            'cashbox_ticket_details',
            'category_prestations',
            'category_tests',
            'chats',
            'clients',
            'consultations',
            'consultation_type_consultation_files',
            'contrats',
            'data_codes',
            'details__contrats',
            'detail_test_orders',
            'docs',
            'doctors',
            'documentation_categories',
            'doc_versions',
            'employees',
            'employee_contrats',
            'employee_documents',
            'employee_payrolls',
            'employee_timeoffs',
            'expence_details',
            'expenses',
            'expense_categories',
            'failed_jobs',
            'hospitals',
            'invoices',
            'invoice_details',
            'log_reports',
            // 'migrations',
            'movements',
            'operations',
            // 'password_resets',
            'patients',
            'payments',
            'permissions',
            // 'personal_access_tokens',
            'prestations',
            'prestation_orders',
            'problem_categories',
            'problem_reports',
            'refund_reasons',
            'refund_requests',
            'refund_request_logs',
            'reports',
            'report_tags',
            'report_titles',
            'ressources',
            'roles',
            'role_permissions',
            'settings',
            'setting_apps',
            'setting_invoices',
            'setting_report_templates',
            'signals',
            'suppliers',
            'supplier_categories',
            'tags',
            'tests',
            'test_orders',
            'test_order_assignments',
            'test_order_assignment_details',
            'test_pathology_macros',
            'tickets',
            'ticket_comments',
            'two_fas',
            'type_consultations',
            'type_consultation_files',
            'type_consultation_type_consultation_file',
            'type_orders',
            'unit_measurements',
            'users',
            'users_permissions',
            'user_roles',
        ];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'branch_id')) {
                DB::table($table)->update(['branch_id' => $branchId]);
            }
        }
    }
}
