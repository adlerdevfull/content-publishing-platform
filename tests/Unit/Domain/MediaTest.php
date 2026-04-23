<?php
declare(strict_types=1);
use Domain\Media\Entities\Media;

test('validates allowed mime types', function () {
    Media::validate('image/jpeg', 1000);
    expect(true)->toBeTrue(); // no exception
});

test('rejects invalid mime type', function () {
    Media::validate('application/x-php', 1000);
})->throws(\DomainException::class);

test('rejects oversized file', function () {
    Media::validate('image/png', 11 * 1024 * 1024);
})->throws(\DomainException::class);

test('detects image', function () {
    $m = new Media(1, 1, 'test.jpg', 'image/jpeg', 5000, '/path');
    expect($m->isImage())->toBeTrue();
});

test('pdf is not image', function () {
    $m = new Media(1, 1, 'doc.pdf', 'application/pdf', 5000, '/path');
    expect($m->isImage())->toBeFalse();
});
