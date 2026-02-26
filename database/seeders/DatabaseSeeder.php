<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Hash};
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'editor', 'author'] as $role) {
            Role::findOrCreate($role, 'api');
        }

        $admin = User::create(['name' => 'Admin', 'email' => 'admin@platform.test', 'password' => Hash::make('password')]);
        $admin->assignRole('admin');

        $editor = User::create(['name' => 'Elena Editora', 'email' => 'editor@platform.test', 'password' => Hash::make('password')]);
        $editor->assignRole('editor');

        $author = User::create(['name' => 'Pablo Autor', 'email' => 'author@platform.test', 'password' => Hash::make('password')]);
        $author->assignRole('author');

        // Categories
        $tech = DB::table('categories')->insertGetId(['name' => 'Tecnología', 'slug' => 'tecnologia', 'parent_id' => null, 'description' => 'Artículos sobre tecnología', 'created_at' => now(), 'updated_at' => now()]);
        $dev = DB::table('categories')->insertGetId(['name' => 'Desarrollo', 'slug' => 'desarrollo', 'parent_id' => $tech, 'description' => 'Programación y desarrollo', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('categories')->insert([
            ['name' => 'DevOps', 'slug' => 'devops', 'parent_id' => $tech, 'description' => 'Infraestructura y CI/CD', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Negocio', 'slug' => 'negocio', 'parent_id' => null, 'description' => 'Estrategia empresarial', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Contents
        $content1 = DB::table('contents')->insertGetId([
            'author_id' => $author->id, 'title' => 'Introducción a DDD en PHP',
            'body' => 'Domain-Driven Design es un enfoque de desarrollo de software que se centra en modelar el dominio del negocio. En este artículo exploraremos cómo aplicar DDD en proyectos PHP con Laravel, incluyendo Entities, Value Objects, y Aggregates.',
            'status' => 'published', 'visibility' => 'public',
            'keywords' => json_encode(['DDD', 'PHP', 'Laravel', 'arquitectura']),
            'translations' => json_encode(['en' => ['title' => 'Introduction to DDD in PHP', 'body' => 'Domain-Driven Design is a software development approach...']]),
            'category_id' => $dev, 'slug' => 'introduccion-ddd-php', 'version' => 2,
            'locked_by' => null, 'publish_at' => now()->subDays(3),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('contents')->insert([
            'author_id' => $editor->id, 'title' => 'Guía de Arquitectura Hexagonal',
            'body' => 'La arquitectura hexagonal, también conocida como Ports and Adapters, nos permite desacoplar la lógica de negocio de los detalles de infraestructura. Veremos cómo implementarla paso a paso.',
            'status' => 'in_review', 'visibility' => 'public',
            'keywords' => json_encode(['hexagonal', 'arquitectura', 'clean code']),
            'translations' => null, 'category_id' => $dev, 'slug' => 'guia-arquitectura-hexagonal',
            'version' => 1, 'locked_by' => $editor->id, 'publish_at' => null,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // Content Versions
        DB::table('content_versions')->insert([
            ['content_id' => $content1, 'version' => 1, 'title' => 'Intro a DDD en PHP', 'body' => 'Borrador inicial del artículo sobre DDD...', 'keywords' => null, 'translations' => null, 'edited_by' => $author->id, 'comment' => 'Primer borrador', 'created_at' => now()->subDays(5), 'updated_at' => now()->subDays(5)],
            ['content_id' => $content1, 'version' => 2, 'title' => 'Introducción a DDD en PHP', 'body' => 'Domain-Driven Design es un enfoque de desarrollo...', 'keywords' => json_encode(['DDD', 'PHP']), 'translations' => null, 'edited_by' => $editor->id, 'comment' => 'Revisión editorial', 'created_at' => now()->subDays(3), 'updated_at' => now()->subDays(3)],
        ]);
    }
}
