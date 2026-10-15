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
        Schema::create('announcements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('message');
            $table->string('format', 10)->default('markdown');
            $table->string('type', 10)->default('bar');
            $table->string('style', 10)->default('info');
            $table->string('color', 7)->nullable();
            $table->string('text_color', 7)->nullable();
            $table->string('button_label', 50)->nullable();
            $table->string('button_url')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('has_countdown')->default(false);
            $table->string('audience', 10)->default('all');
            $table->json('roles')->nullable();
            $table->string('pages', 10)->default('all');
            $table->text('page_patterns')->nullable();
            $table->boolean('is_dismissible')->default(true);
            $table->string('frequency', 10)->default('once');
            $table->integer('priority')->default(0);
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();

            $table->index(['is_enabled', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
