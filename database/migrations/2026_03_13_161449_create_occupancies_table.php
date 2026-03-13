<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('occupancies', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
            $table->enum('work_mode', ['On-site', 'Hybrid', 'Remote']);
            $table->longText('job_description');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('occupancies');
    }
};
