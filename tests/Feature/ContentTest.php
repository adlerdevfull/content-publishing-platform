<?php
declare(strict_types=1);
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    $this->user = User::factory()->create();
    $this->user->assignRole('admin');
    $this->token = auth('api')->login($this->user);
});

test('can create content', function () {
    $response = $this->withToken($this->token)->postJson('/api/v1/contents', [
        'title' => 'My Article', 'body' => 'Article body content', 'keywords' => ['php','laravel'],
    ]);
    $response->assertStatus(201)->assertJsonPath('data.title', 'My Article')->assertJsonPath('data.version', 1);
});

test('update creates version', function () {
    $create = $this->withToken($this->token)->postJson('/api/v1/contents', ['title' => 'V1', 'body' => 'Body v1']);
    $id = $create->json('data.id');

    $this->withToken($this->token)->putJson("/api/v1/contents/{$id}", ['title' => 'V2', 'comment' => 'Updated title']);

    $versions = $this->withToken($this->token)->getJson("/api/v1/contents/{$id}/versions");
    $versions->assertOk();
    expect($versions->json('data'))->toHaveCount(1);
});

test('can transition through workflow', function () {
    $create = $this->withToken($this->token)->postJson('/api/v1/contents', ['title' => 'T', 'body' => 'B']);
    $id = $create->json('data.id');

    $this->withToken($this->token)->patchJson("/api/v1/contents/{$id}/transition", ['status' => 'in_review'])->assertOk();
    $this->withToken($this->token)->patchJson("/api/v1/contents/{$id}/transition", ['status' => 'approved'])->assertOk();
    $this->withToken($this->token)->patchJson("/api/v1/contents/{$id}/transition", ['status' => 'published'])->assertOk()->assertJsonPath('data.status', 'published');
});

test('cannot skip workflow steps', function () {
    $create = $this->withToken($this->token)->postJson('/api/v1/contents', ['title' => 'T', 'body' => 'B']);
    $id = $create->json('data.id');
    $this->withToken($this->token)->patchJson("/api/v1/contents/{$id}/transition", ['status' => 'published'])->assertStatus(422);
});
