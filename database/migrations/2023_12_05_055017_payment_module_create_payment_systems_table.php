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
        $tableName = 'payments.payment_systems';

        Schema::create($tableName, static function (Blueprint $table) {
            $table->id()->comment('Уникальный идентификатор');
            $table->string('name')->comment('Имя системы');
            $table->string('full_name')->comment('Полное имя системы');
            $table->boolean('is_active')->default(1)->comment('Флаг действия справочного значения');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        \Illuminate\Support\Facades\DB::table($tableName)->insert(
            [
                ['name' => 'Сбербанк', 'full_name' => 'Платеж через систему Сбербанка'],
                ['name' => 'Apple Pay', 'full_name' => 'Платеж через Apple Pay'],
                ['name' => 'Samsung Pay', 'full_name' => 'Платеж через Samsung Pay'],
                ['name' => 'Google Pay', 'full_name' => 'Платеж через Google Pay'],
            ]
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
        Schema::dropIfExists('payments.payment_systems');
    }
};
