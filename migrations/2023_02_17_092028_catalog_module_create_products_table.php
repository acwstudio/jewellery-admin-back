<?php

use App\Packages\Enums\LiquidityEnum;
use Illuminate\Database\Grammar;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Grammar::macro('typeLiquidity', function () {
            return 'catalog.liquidity';
        });

        $cases = implode(',', array_map(function ($unit) {
            return "'" . $unit->value . "'";
        }, LiquidityEnum::cases()));

        DB::unprepared(sprintf("CREATE TYPE catalog.liquidity AS ENUM (%s);", $cases));

        Schema::create('catalog.products', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('name');
            $table->string('summary');
            $table->string('description');
            $table->string('catalog_number')->nullable();
            $table->string('supplier')->nullable();
            $table->addColumn('liquidity', 'liquidity')->nullable();
            $table->float('stamp')->nullable();
            $table->string('manufacture_country');
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_drop_shipping')->nullable()->default(null);
            $table->integer('rank');
            $table->integer('popularity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog.products');

        DB::unprepared("DROP TYPE IF EXISTS catalog.liquidity");
    }
};
