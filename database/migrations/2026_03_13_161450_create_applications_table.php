<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('occupancy_id')->constrained('occupancies')->cascadeOnDelete();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone_number');
            $table->text('message')->nullable();
            $table->string('file_path');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
