<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('otc_requests', function (Blueprint $table) {
            $table->decimal('transaction_cost', 16, 8)->default(0)->after('network_fee');
        });

        Schema::table('asset_transfers', function (Blueprint $table) {
            $table->decimal('transaction_cost', 16, 8)->default(0)->after('fee');
        });
    }

    public function down(): void
    {
        Schema::table('otc_requests', function (Blueprint $table) {
            $table->dropColumn('transaction_cost');
        });

        Schema::table('asset_transfers', function (Blueprint $table) {
            $table->dropColumn('transaction_cost');
        });
    }
}; 