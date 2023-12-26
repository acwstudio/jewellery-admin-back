<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up(): void
    {
        $tableName = 'payments.payments';

        Schema::create('payments.payments', static function (Blueprint $table) {
            $systemsTableName = 'payments.payment_systems';
            $statusesTableName = 'payments.payment_statuses';
            $table->id()->comment('Уникальный идентификатор');
            $table->string('bank_order_id', 36)->nullable()->comment('Номер заказа в платежной системе');
            $table->unsignedInteger('status_id')->comment('id статуса заказа');
            $table->unsignedInteger('system_id')->comment('id вида платежной системы');
            $table->morphs('payment', 'payment_type_payment_id_index');
            $table->timestamps();

            $table->foreign('status_id', "payments_status_id_foreign")
                ->references('id')
                ->on($statusesTableName)
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('system_id', "payments_system_id_foreign")
                ->references('id')
                ->on($systemsTableName)
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down(): void
    {
        $tableName = 'payments.payments';
//        if (DB::getDriverName() !== 'sqlite') {
//            Schema::table($tableName, static function (Blueprint $table) {
//                $statusesTableName = 'payments.payment_statuses';
//                $table->dropForeign("{$statusesTableName}_status_id_foreign");
//                $table->dropIndex('payment_type_payment_id_index');
//            });
//        }
        Schema::dropIfExists($tableName);
    }
};
