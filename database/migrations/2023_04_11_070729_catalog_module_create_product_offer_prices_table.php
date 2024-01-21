<?php

use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
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
        Grammar::macro('typeOfferPrice', function () {
            return 'catalog.offerPrice';
        });

        $cases = implode(',', array_map(function ($unit) {
            return "'" . $unit->value . "'";
        }, OfferPriceTypeEnum::cases()));

        DB::unprepared(sprintf("CREATE TYPE catalog.offerPrice AS ENUM (%s);", $cases));

        Schema::create('catalog.product_offer_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_offer_id');
            $table->integer('price');
            $table->addColumn('offerPrice', 'type');
            $table->boolean('is_active');
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
        Schema::dropIfExists('catalog.product_offer_prices');
    }
};
