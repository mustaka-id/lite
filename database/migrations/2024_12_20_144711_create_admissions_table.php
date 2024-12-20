<?php

use App\Models\Admission\Registrant;
use App\Models\Admission\RegistrantBill;
use App\Models\Admission\Wave;
use App\Models\Employee;
use App\Models\User;
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
        Schema::create('adm_waves', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Year::class)->constrained(app(Year::class)->getTable())->cascadeOnUpdate();
            $table->string('name');
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('adm_registrant', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Wave::class)->constrained(app(Wave::class)->getTable())->cascadeOnUpdate();
            $table->foreignIdFor(User::class)->constrained(app(User::class)->getTable())->cascadeOnUpdate();
            $table->foreignIdFor(User::class, 'registered_by')->nullable()->constrained(app(User::class)->getTable())->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('paid_off_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('adm_registrant_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Wave::class)->constrained(app(Wave::class)->getTable())->cascadeOnUpdate();
            $table->foreignIdFor(Registrant::class)->constrained(app(Registrant::class)->getTable())->cascadeOnUpdate();
            $table->string('category')->nullable();
            $table->string('sequence');
            $table->string('name');
            $table->double('amount')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('adm_registrant_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Registrant::class)->constrained(app(Registrant::class)->getTable())->cascadeOnUpdate();
            $table->foreignIdFor(RegistrantBill::class, 'bill_id')->constrained(app(RegistrantBill::class)->getTable())->cascadeOnUpdate();
            $table->string('code')->unique(); // 'TRX-'.time();
            $table->string('name')->nullable();
            $table->double('amount');
            $table->string('method')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignIdFor(User::class, 'payer_id')->constrained(app(User::class)->getTable())->cascadeOnUpdate();
            $table->foreignIdFor(Employee::class, 'receiver_id')->constrained(app(Employee::class)->getTable())->cascadeOnUpdate();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm_registrant_payments');
        Schema::dropIfExists('adm_registrant_bills');
        Schema::dropIfExists('adm_registrant');
        Schema::dropIfExists('adm_waves');
    }
};
