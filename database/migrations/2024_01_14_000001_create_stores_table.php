
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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('address_ar')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('tax_rate', 5, 2)->default(0.00);
            $table->string('currency', 3)->default('SAR');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
