<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('content_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('contents')->onDelete('cascade');
            $table->integer('version'); $table->string('title', 500); $table->text('body');
            $table->json('keywords')->nullable(); $table->json('translations')->nullable();
            $table->foreignId('edited_by')->constrained('users');
            $table->text('comment')->nullable(); $table->timestamps();
            $table->index(['content_id','version']);
        });
    }
    public function down(): void { Schema::dropIfExists('content_versions'); }
};
