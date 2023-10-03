<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Ressource;
use Illuminate\Database\Seeder;

class NewPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Peut ajouter une dépense à la caisse
            $res_add_cashbox_expense = Ressource::updateOrCreate([
                'titre' => "add_cashbox_expense",
                'slug' => "add_cashbox_expense",
            ]);

            Permission::updateOrCreate([
                'titre' =>"view add_cashbox_expense",
                'slug' => "view-add-cashbox-expense",
                'operation_id' => 1,
                'ressource_id' => $res_add_cashbox_expense->id,
            ]);
        //Fin Peut ajouter une dépense à la caisse

        /**Peut gérer les transactions, marquer une facture comme payée, ouvrir/fermer la caisse */
        // cashier
            $res_cashier = Ressource::updateOrCreate([
                'titre' => "cashier",
                'slug' => "cashier",
            ]);

            Permission::updateOrCreate([
                'titre' =>"view cashier",
                'slug' => "view-cashier",
                'operation_id' => 1,
                'ressource_id' => $res_cashier->id,
            ]);
        // Fin cashier

        /**Peut traiter les tickets de bon de caisse */
        // process_cashbox_tickets
            $res_process_cashbox_tickets = Ressource::updateOrCreate([
                'titre' => "process_cashbox_tickets",
                'slug' => "process_cashbox_tickets",
            ]);

            Permission::updateOrCreate([
                'titre' =>"view process_cashbox_tickets",
                'slug' => "view-process-cashbox-tickets",
                'operation_id' => 1,
                'ressource_id' => $res_process_cashbox_tickets->id,
            ]);
        // Fin process_cashbox_tickets

        /**Peut traiter les demandes de remboursement */
        // process_refund_request
            $res_process_refund_request = Ressource::updateOrCreate([
                'titre' => "process_refund_request",
                'slug' => "process_refund_request",
            ]);

            Permission::updateOrCreate([
                'titre' =>"view process_refund_request",
                'slug' => "view-process-refund-request",
                'operation_id' => 1,
                'ressource_id' => $res_process_refund_request->id,
            ]);
        // Fin process_cashbox_tickets

        /**Peut traiter les tickets */
        // process_ticket
            $res_process_ticket = Ressource::updateOrCreate([
                'titre' => "process_ticket",
                'slug' => "process_ticket",
            ]);

            Permission::updateOrCreate([
                'titre' =>"view process_ticket",
                'slug' => "view-process-ticket",
                'operation_id' => 1,
                'ressource_id' => $res_process_ticket->id,
            ]);
        // Fin process_cashbox_tickets

        /**Peut gérer les consultations et la chimiothérapie */
        // oncologist
            $res_oncologist = Ressource::updateOrCreate([
                'titre' => "oncologist",
                'slug' => "oncologist",
            ]);

            Permission::updateOrCreate([
                'titre' =>"view oncologist",
                'slug' => "view-oncologist",
                'operation_id' => 1,
                'ressource_id' => $res_oncologist->id,
            ]);
        // Fin oncologist

        /**Peut mettre à jour la partie signature d'un compte rendu, si la demande lui est affectée, peut visualiser le tableau de bord le concernant */
        // pathologist
            $res_pathologist = Ressource::updateOrCreate([
                'titre' => "pathologist",
                'slug' => "pathologist",
            ]);

            Permission::updateOrCreate([
                'titre' =>"view pathologist",
                'slug' => "view-pathologist",
                'operation_id' => 1,
                'ressource_id' => $res_pathologist->id,
            ]);
        // Fin process_cashbox_tickets

        // admin_dashboard
            $ressource1 = Ressource::updateOrCreate([
                'titre' => "admin_dashboard",
                'slug' => "admin_dashboard",
            ]);

            $permission1 = Permission::updateOrCreate([
                'titre' =>"view admin_dashboard",
                'slug' => "view-admin-dashboard",
                'operation_id' => 1,
                'ressource_id' => $ressource1->id,
            ]);
        //Fin admin_dashboard

        //secretariat_dashboard
            $ressource2 = Ressource::updateOrCreate([
                'titre' => "secretariat_dashboard",
                'slug' => "secretariat_dashboard",
            ]);

            $permission2 = Permission::updateOrCreate([
                'titre' =>"view secretariat_dashboard",
                'slug' => "view-secretariat-dashboard",
                'operation_id' => 1,
                'ressource_id' => $ressource2->id,
            ]);
        // Fin secretariat_dashboard
    }
}
