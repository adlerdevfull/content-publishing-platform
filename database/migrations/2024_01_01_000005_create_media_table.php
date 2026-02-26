<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->string('filename'); $table->string('mime_type', 100);
            $table->integer('size_bytes'); $table->string('path');
            $table->string('thumbnail_path')->nullable();
            $table->foreignId('content_id')->nullable()->constrained('contents')->onDelete('set null');
            $table->string('disk', 20)->default('local');
            $table->timestamps();
            $table->index('content_id');
        });
    }
    public function down(): void { Schema::dropIfExists('media'); }
};
