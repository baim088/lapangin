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
        // 1. Add owner_id to venues
        Schema::table('venues', function (Blueprint $table) {
            $table->foreignId('owner_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });

        // 2. Add DP & settlement tracking to bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_type')->default('full')->after('notes'); // 'full' or 'dp'
            $table->unsignedInteger('dp_percentage')->default(50)->after('payment_type');
            $table->unsignedBigInteger('dp_amount')->default(0)->after('dp_percentage');
            $table->unsignedBigInteger('paid_amount')->default(0)->after('dp_amount');
            $table->unsignedBigInteger('remaining_amount')->default(0)->after('paid_amount');
            $table->string('settlement_method')->nullable()->after('remaining_amount'); // 'cash', 'transfer', 'qris'
            $table->timestamp('settled_at')->nullable()->after('settlement_method');
            $table->foreignId('settled_by')->nullable()->after('settled_at')->constrained('users')->nullOnDelete();
        });

        // 3. Add payment_type and cashier_id to payments
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_type')->default('full')->after('status'); // 'full', 'dp', 'settlement'
            $table->foreignId('cashier_id')->nullable()->after('verified_by')->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable()->after('external_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['cashier_id']);
            $table->dropColumn(['payment_type', 'cashier_id', 'notes']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['settled_by']);
            $table->dropColumn([
                'payment_type',
                'dp_percentage',
                'dp_amount',
                'paid_amount',
                'remaining_amount',
                'settlement_method',
                'settled_at',
                'settled_by',
            ]);
        });

        Schema::table('venues', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropColumn(['owner_id']);
        });
    }
};
