<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('portal_placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portal_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('external_url')->comment('URL when we post our link');
            $table->boolean('ping_counter')->default(false);
            $table->boolean('get_to_ping')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portal_placements');
    }
};
