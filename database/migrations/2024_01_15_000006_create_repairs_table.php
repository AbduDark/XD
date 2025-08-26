<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->string('repair_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('device_type');
            $table->text('problem_description');
            $table->enum('repair_type', ['hardware', 'software']);
            $table->decimal('repair_cost', 10, 2);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'delivered'])->default('pending');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('repairs');
    }
};
