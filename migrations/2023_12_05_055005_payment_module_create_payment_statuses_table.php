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
     * @throws Exception
     */
    public function up(): void
    {
        Schema::create('payments.payment_statuses', static function (Blueprint $table) {
            $table->id()->comment('Уникальный идентификатор');
            $table->unsignedInteger('bank_id')
                ->nullable()
                ->unique()
                ->comment('id статуса в системе банка');
            $table->string('name')->comment('Имя статуса');
            $table->string('full_name')->comment('Полное имя статуса');
            $table->boolean('is_active')
                ->default(1)
                ->comment('Флаг действия справочного значения');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
        \Illuminate\Support\Facades\DB::table('payments.payment_statuses')->insert(
            [
                [
                    'name'      => 'Новый',
                    'full_name' => 'Платеж не зарегистрирован в системе банка',
                    'bank_id'   => null,
                ],
                [
                    'name' => 'Зарегистрирован',
                    'full_name' => 'Платеж зарегистрирован, но не оплачен',
                    'bank_id' => 0
                ],
                [
                    'name' => 'Захолдирован',
                    'full_name' => 'Предавторизованная сумма захолдирована',
                    'bank_id' => 1
                ],
                [
                    'name' => 'Подтвержден',
                    'full_name' => 'Проведена полная авторизация суммы',
                    'bank_id' => 2
                ],
                [
                    'name' => 'Отменен',
                    'full_name' => 'Авторизация отменена',
                    'bank_id' => 3
                ],

                ['name'      => 'Оформлен возврат',
                 'full_name' => 'По транзакции была проведена операция возврата',
                 'bank_id'   => 4,
                ],
                [
                    'name'      => 'ACS-авторизация',
                 'full_name' => 'Инициирована авторизация через ACS банка-эмитента',
                 'bank_id'   => 5,
                ],
                [
                    'name' => 'Отклонен',
                    'full_name' => 'Авторизация отклонена',
                    'bank_id' => 6
                ],
                [
                    'name' => 'Ошибка',
                    'full_name' => 'Системная ошибка при обработке платежа',
                    'bank_id' => null
                ],
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
        Schema::dropIfExists('payments.payment_statuses');
    }
};
