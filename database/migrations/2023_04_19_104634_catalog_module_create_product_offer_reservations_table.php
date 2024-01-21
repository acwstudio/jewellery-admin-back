<?php

use App\Packages\Enums\Catalog\OfferReservationStatusEnum;
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
        Grammar::macro('typeOfferReservationStatus', function () {
            return 'catalog.offerReservationStatus';
        });

        $cases = implode(',', array_map(function ($unit) {
            return "'" . $unit->value . "'";
        }, OfferReservationStatusEnum::cases()));

        DB::unprepared(sprintf("CREATE TYPE catalog.offerReservationStatus AS ENUM (%s);", $cases));

        Schema::create('catalog.product_offer_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_offer_id');
            $table->integer('count');
            $table->addColumn('offerReservationStatus', 'status');
            $table->timestamps();

            $table
                ->foreign('product_offer_id')
                ->references('id')->on('catalog.product_offers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog.product_offer_reservations');
        DB::unprepared("DROP TYPE IF EXISTS catalog.offerReservationStatus");
    }
};
