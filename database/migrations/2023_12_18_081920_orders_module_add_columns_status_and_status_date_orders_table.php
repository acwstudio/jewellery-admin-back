<?php

declare(strict_types=1);

use App\Packages\Enums\Orders\OrderStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders.orders', static function (Blueprint $table) {
            $table->string('status')->default(OrderStatusEnum::CREATED->value);
            $table->timestamp('status_date')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::table('orders.orders', static function (Blueprint $table) {
            $table->dropColumn(['status', 'status_date']);
        });
    }
};
