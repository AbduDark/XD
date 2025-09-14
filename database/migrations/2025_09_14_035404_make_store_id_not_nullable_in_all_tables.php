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
        // Make store_id not nullable in products table
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable(false)->change();
        });

        // Make store_id not nullable in invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable(false)->change();
        });

        // Make store_id not nullable in repairs table
        Schema::table('repairs', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable(false)->change();
        });

        // Make store_id not nullable in returns table
        Schema::table('returns', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable(false)->change();
        });

        // Make store_id not nullable in cash_transfers table
        Schema::table('cash_transfers', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Make store_id nullable again in products table
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->change();
        });

        // Make store_id nullable again in invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->change();
        });

        // Make store_id nullable again in repairs table
        Schema::table('repairs', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->change();
        });

        // Make store_id nullable again in returns table
        Schema::table('returns', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->change();
        });

        // Make store_id nullable again in cash_transfers table
        Schema::table('cash_transfers', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->change();
        });
    }
};