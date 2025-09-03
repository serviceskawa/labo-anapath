<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
{
    $tables = [
        'appel_by_reports',
        'appel_test_oders',
        'appointments',
        'articles',
        'assignment_doctors',
        'banks',
        // 'branches',
        // 'branch_user',
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
        // 'users',
        'users_permissions',
        'user_roles',
    ];

    foreach ($tables as $tbl) {
        if (Schema::hasTable($tbl) && !Schema::hasColumn($tbl, 'branch_id')) {
            Schema::table($tbl, function (Blueprint $table) {
                $table->foreignId('branch_id')
                    ->nullable()
                    ->constrained('branches')
                    ->nullOnDelete()
                    ->comment('branch_id clé étrangère');
            });
        }
    }
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = [
            'appel_by_reports',
            'appel_test_oders',
            'appointments',
            'articles',
            'assignment_doctors',
            'banks',
            // 'branches',
            // 'branch_user',
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
            // 'users',
            'users_permissions',
            'user_roles',
        ];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'branch_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign([$table->getTable() . '_branch_id_foreign']);
                    $table->dropColumn('branch_id');
                });
            }
        }
    }
}
