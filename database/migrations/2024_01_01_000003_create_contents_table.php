<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users');
            $table->string('title', 500); $table->text('body');
            $table->string('status', 20)->default('draft');
            $table->string('visibility', 20)->default('public');
            $table->json('keywords')->nullable(); $table->json('translations')->nullable();
            $table->foreignId('category_id')->nullable()->constrained();
            $table->json('tag_ids')->nullable();
            $table->string('slug', 500)->nullable();
            $table->integer('version')->default(1);
            $table->foreignId('locked_by')->nullable()->constrained('users');
            $table->timestamp('publish_at')->nullable();
            $table->timestamps();
            $table->index('status'); $table->index('slug'); $table->index(['author_id','status']); $table->index('category_id');
        });
        // Full-text search index (PostgreSQL)
        DB::statement("CREATE INDEX contents_fulltext_idx ON contents USING gin(to_tsvector('spanish', title || ' ' || body))");
    }
    public function down(): void { Schema::dropIfExists('contents'); }
};
