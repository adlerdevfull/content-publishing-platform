<?php
declare(strict_types=1);
use Domain\Content\Entities\{Content, ContentVersion};
use Domain\Content\Enums\ContentStatus;

test('creates version from content', function () {
    $content = new Content(5, 1, 'Title', 'Body', ContentStatus::Draft, keywords: ['test'], version: 3);
    $version = ContentVersion::fromContent($content, 2, 'Review comment');

    expect($version->contentId)->toBe(5);
    expect($version->version)->toBe(3);
    expect($version->title)->toBe('Title');
    expect($version->editedBy)->toBe(2);
    expect($version->comment)->toBe('Review comment');
});
