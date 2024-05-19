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
        Schema::create('article_notification', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id');
			$table->foreign('subscription_id')->references('id')->on('subscription')->onDelete('cascade');
            $table->foreignId('article_id');
			$table->foreign('article_id')->references('id')->on('article')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_notification');
    }
};
