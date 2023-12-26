<?php

declare(strict_types=1);

use App\Packages\Enums\Orders\DeliveryType;
use Illuminate\Database\Grammar;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createDeliveryTypeEnumType();

        Schema::create('orders.deliveries', function (Blueprint $table) {
            $table->id();
            $table->addColumn('deliveryType', 'delivery_type');
            $table->string('price');
            $table->integer('pvz_id')->nullable();
            $table->string('currier_delivery_id')->nullable();
            $table->timestamps();
            $table->bigInteger('order_id');

            $table
                ->foreign('order_id')
                ->references('id')->on('orders.orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders.deliveries');
        DB::unprepared("DROP TYPE IF EXISTS catalog.liquidity");
    }

    private function createDeliveryTypeEnumType(): void
    {
        Grammar::macro('typeDeliveryType', function () {
            return 'orders.deliveryType';
        });

        $cases = implode(',', array_map(function ($unit) {
            return "'" . $unit->value . "'";
        }, DeliveryType::cases()));

        DB::unprepared(sprintf("CREATE TYPE orders.deliveryType AS ENUM (%s);", $cases));
    }
};
