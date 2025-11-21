<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rename customer_id to user_id if it exists
            if (Schema::hasColumn('orders', 'customer_id')) {
                $table->renameColumn('customer_id', 'user_id');
            } else {
                $table->foreignId('user_id')->nullable()->after('id');
            }

            // Add missing columns
            $table->decimal('total_amount', 10, 2)->default(0)->after('total');
            $table->string('shipping_address')->nullable()->after('status');
            $table->string('billing_address')->nullable()->after('shipping_address');
            $table->string('payment_status')->default('pending')->after('billing_address');
            $table->string('phone')->nullable()->after('payment_status');
            
            // Update the status column to include our statuses
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert the changes if needed
            if (Schema::hasColumn('orders', 'user_id')) {
                $table->renameColumn('user_id', 'customer_id');
            }
            
            $table->dropColumn([
                'total_amount',
                'shipping_address',
                'billing_address',
                'payment_status',
                'phone'
            ]);
        });
    }
};