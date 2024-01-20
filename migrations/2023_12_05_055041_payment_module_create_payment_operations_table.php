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
    public function up()
    {
        $tableName = 'payments.payment_operations';

        Schema::create(
            $tableName,
            static function (Blueprint $table) {
                $paymentsTableName = 'payments.payments';
                $operationTypesTableName = 'payments.payment_operation_types';
                $table->id()->comment('Уникальный идентификатор');
                $table->unsignedBigInteger('payment_id')->comment('id платежа');
                $table->uuid('user_id')->nullable()->comment('id пользователя-инициатора операции');
                $table->unsignedBigInteger('type_id')->comment('id типа операции');
                $table->text('request_json')->comment('JSON с данными запроса к банку');
                $table->text('response_json')->nullable()->comment('JSON с ответом от банка');
                $table->timestamps();

                $table->foreign('payment_id', "payments_payment_id_foreign")
                    ->references('id')
                    ->on($paymentsTableName)
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

                $table->foreign('type_id', "payment_operation_types_operation_type_id_foreign")
                    ->references('id')
                    ->on($operationTypesTableName)
                    ->onUpdate('cascade')
                    ->onDelete('restrict');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down(): void
    {
        $tableName = 'payments.payment_operations';

//        if (DB::getDriverName() !== 'sqlite') {
//            Schema::table(
//                $tableName,
//                static function (Blueprint $table) {
//                    $operationTypesTableName = 'payments.payment_operation_types';
//                    $paymentsTableName = 'payments.payments';
//                    $table->dropForeign("{$paymentsTableName}_payment_id_foreign");
//                    $table->dropForeign("{$operationTypesTableName}_operation_type_id_foreign");
//                }
//            );
//        }
        Schema::dropIfExists($tableName);
    }
};
