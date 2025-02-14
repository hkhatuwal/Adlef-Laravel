<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('activity_type'); // e.g., 'asset_transfer', 'otc_trade', etc.
            $table->string('action'); // e.g., 'transfer_in', 'transfer_out', 'completed', 'cancelled'
            $table->morphs('subject'); // Polymorphic relationship to the related model
            $table->string('status'); // e.g., 'pending', 'completed', 'failed', 'cancelled'
            $table->decimal('amount', 20, 8)->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('reference_number')->nullable();
            $table->json('metadata')->nullable(); // Store any additional data
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'created_at']);
            $table->index(['activity_type', 'status']);
            $table->index('reference_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_activities');
    }
}; 