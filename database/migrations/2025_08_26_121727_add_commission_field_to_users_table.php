<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionFieldToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Add commission, whatsapp, and telephone fields to users table
     * to enhance user profile and contact information
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if commission column doesn't already exist
            if (!Schema::hasColumn('users', 'commission')) {
                $table->decimal('commission', 10, 2)
                    ->nullable()
                    ->default(0.00)
                    ->comment('User commission')
                    ->after('email');
            }

            // Check if whatsapp column doesn't already exist
            if (!Schema::hasColumn('users', 'whatsapp')) {
                $table->string('whatsapp')
                    ->nullable()
                    ->comment('WhatsApp phone number with country code')
                    ->after('commission');
            }

            // Check if telephone column doesn't already exist
            if (!Schema::hasColumn('users', 'telephone')) {
                $table->string('telephone')
                    ->nullable()
                    ->comment('Primary telephone number')
                    ->after('whatsapp');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * Remove commission, whatsapp, and telephone fields from users table
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToRemove = ['commission', 'whatsapp', 'telephone'];

            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
