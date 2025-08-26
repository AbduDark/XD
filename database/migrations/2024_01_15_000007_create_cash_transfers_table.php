<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cash_transfers', function (Blueprint $table) {
            $table->id();
            $table->enum('service', ['vodafone_cash', 'etisalat_cash', 'orange_cash', 'access_cash', 'cards']);
            $table->string('service_ar');
            $table->decimal('amount', 10, 2);
            $table->decimal('commission', 10, 2);
            $table->string('customer_phone')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cash_transfers');
    }
};
