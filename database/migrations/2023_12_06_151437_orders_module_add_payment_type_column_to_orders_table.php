<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'orders.orders',
            static function (Blueprint $table) {
                $table->string('payment_type')->nullable()->comment('Тип платежа');
                $table->unsignedBigInteger('payment_id', false)
                    ->nullable()
                    ->comment('ID платежа');
            },
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table(
            'orders.orders',
            static function (Blueprint $table) {
                $table->dropColumn('payment_type');
                $table->dropColumn('payment_id');
            },
        );
    }
};
