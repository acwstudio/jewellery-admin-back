<?php

declare(strict_types=1);

use App\Packages\Enums\Users\RoleEnum;
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
        Grammar::macro('typeRole', function () {
            return 'users.role';
        });

        $cases = implode(',', array_map(function ($unit) {
            return "'" . $unit->value . "'";
        }, RoleEnum::cases()));

        DB::unprepared(sprintf("CREATE TYPE users.Role AS ENUM (%s);", $cases));

        Schema::create('users.roles', function (Blueprint $table) {
            $table->id();
            $table->addColumn('role', 'type')->unique();
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
        Schema::dropIfExists('users.roles');
        DB::unprepared("DROP TYPE IF EXISTS users.role");
    }
};
