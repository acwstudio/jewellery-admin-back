<?php

use App\Packages\Enums\Catalog\OfferStockReasonEnum;
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
        Grammar::macro('typeOfferStockReason', function () {
            return 'catalog.offerStockReason';
        });

        $cases = implode(',', array_map(function ($unit) {
            return "'" . $unit->value . "'";
        }, OfferStockReasonEnum::cases()));

        DB::unprepared(sprintf("CREATE TYPE catalog.offerStockReason AS ENUM (%s);", $cases));

        Schema::create('catalog.product_offer_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_offer_id');
            $table->integer('count');
            $table->addColumn('offerStockReason', 'reason');
            $table->boolean('is_current');
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
        Schema::dropIfExists('catalog.product_offer_stocks');
        DB::unprepared("DROP TYPE IF EXISTS catalog.offerStockReason");
    }
};
