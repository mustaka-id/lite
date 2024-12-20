<?php

use App\Models\Employee;
use App\Models\Year;
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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Year::class)->constrained(app(Year::class)->getTable())->cascadeOnUpdate();
            $table->string('grade');
            $table->unsignedTinyInteger('level');
            $table->string('name');
            $table->string('capacity')->nullable();
            $table->foreignIdFor(Employee::class, 'homeroom_id')->nullable()->constrained(app(Employee::class)->getTable())->cascadeOnUpdate()->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
