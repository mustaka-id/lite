<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->nullable()->unique();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->json('roles')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('user_profile', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->constrained(app(User::class)->getTable())->cascadeOnDelete();
            $table->string('pob')->nullable();
            $table->date('dob')->nullable();
            $table->unsignedTinyInteger('sex')->nullable();
            $table->string('nisn')->nullable()->unique();
            $table->string('kk_number')->nullable();
            $table->boolean('is_alive')->nullable()->default(true);
            $table->unsignedTinyInteger('siblings_count')->nullable();
            $table->unsignedTinyInteger('child_order')->nullable();
            $table->string('religion')->nullable();
            $table->string('aspiration')->nullable();
            $table->string('last_education_grade')->nullable();
            $table->string('monthly_income')->nullable();
            $table->string('nationality')->nullable();
            $table->timestamps();

            $table->primary('user_id');
        });

        Schema::create('user_parent', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignIdFor(User::class)->constrained(app(User::class)->getTable());
            $table->foreignIdFor(User::class, 'parent_id')->constrained(app(User::class)->getTable());
            $table->timestamps();

            $table->unique(['type', 'user_id', 'parent_id']);
        });

        Schema::create('user_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained(app(User::class)->getTable());
            $table->string('grade');
            $table->string('name');
            $table->string('npsn')->nullable();
            $table->string('certificate')->nullable();
            $table->string('certificate_number')->nullable();
            $table->timestamps();
        });

        Schema::create('user_files', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained(app(User::class)->getTable());
            $table->string('category')->nullable();
            $table->string('name');
            $table->string('path')->nullable();
            $table->boolean('required')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignIdFor(User::class)->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('user_parent');
        Schema::dropIfExists('user_profile');
        Schema::dropIfExists('users');
    }
};
