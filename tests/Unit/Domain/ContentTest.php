<?php
declare(strict_types=1);
use Domain\Content\Entities\Content;
use Domain\Content\Enums\{ContentStatus, Visibility};

test('draft transitions to in_review', function () {
    $c = new Content(1, 1, 'Title', 'Body');
    $c->transitionTo(ContentStatus::InReview);
    expect($c->status)->toBe(ContentStatus::InReview);
});

test('cannot skip from draft to published', function () {
    $c = new Content(1, 1, 'Title', 'Body');
    $c->transitionTo(ContentStatus::Published);
})->throws(\DomainException::class);

test('archived can go back to draft', function () {
    $c = new Content(1, 1, 'T', 'B', ContentStatus::Archived);
    $c->transitionTo(ContentStatus::Draft);
    expect($c->status)->toBe(ContentStatus::Draft);
});

test('lock prevents other users', function () {
    $c = new Content(1, 1, 'T', 'B');
    $c->lock(1);
    $c->lock(2);
})->throws(\DomainException::class);

test('same user can re-lock', function () {
    $c = new Content(1, 1, 'T', 'B');
    $c->lock(1);
    $c->lock(1); // no exception
    expect($c->lockedBy)->toBe(1);
});

test('generates slug', function () {
    $c = new Content(1, 1, 'Hello World Test!', 'B');
    $c->generateSlug();
    expect($c->slug)->toBe('hello-world-test');
});

test('increments version', function () {
    $c = new Content(1, 1, 'T', 'B', version: 3);
    $c->incrementVersion();
    expect($c->version)->toBe(4);
});
