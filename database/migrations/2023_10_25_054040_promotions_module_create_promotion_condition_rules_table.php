<?php

declare(strict_types=1);

use App\Packages\Enums\OperatorEnum;
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
        Grammar::macro('typeOperator', function () {
            return 'promotions.operator';
        });

        $cases = implode(',', array_map(function ($unit) {
            return "'" . $unit->value . "'";
        }, OperatorEnum::cases()));

        DB::unprepared(sprintf("CREATE TYPE promotions.operator AS ENUM (%s);", $cases));

        Schema::create('promotions.promotion_condition_rules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('promotion_condition_id');
            $table->string('type')->nullable(false);
            $table->string('total_amount')->nullable();
            $table->integer('total_count')->nullable();
            $table->addColumn('operator', 'operator')->nullable();
            $table->string('feature_name')->nullable();
            $table->string('feature_value')->nullable();
            $table->timestamps();

            $table
                ->foreign('promotion_condition_id')
                ->references('id')->on('promotions.promotion_conditions')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotions.promotion_condition_rules');
        DB::unprepared("DROP TYPE IF EXISTS promotions.operator");
    }
};
