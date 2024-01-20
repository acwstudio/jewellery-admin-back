<?php

use App\Modules\Stores\Enums\StoreWorkDayEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        foreach (StoreWorkDayEnum::cases() as $enum) {
            $enums[] = $enum->value;
        }

        Schema::create('stores.store_work_times', function (Blueprint $table) use ($enums){
            $table->id();

            $table->integer('store_id');
            $table->enum('day', $enums)->comment('День недели');
            $table->time('start_time', 0)->comment('Время начала работы');
            $table->time('end_time', 0)->comment('Время конца работы');
            $table->timestamps();
            $table
                ->foreign('store_id')
                ->references('id')->on('stores.stores')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores.store_work_times');
    }
};
