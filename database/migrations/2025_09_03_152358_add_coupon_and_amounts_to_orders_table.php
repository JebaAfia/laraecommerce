<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('total_amount')->after('product_id');
            $table->string('payable_amount')->after('total_amount');

            $table->string('coupon_code')->nullable()->after('payable_amount');

            $table->foreign('coupon_code')->references('coupon_code')->on('coupons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_code']);
            $table->dropColumn(['total_amount', 'payable_amount', 'coupon_code']);
        });
    }
};
