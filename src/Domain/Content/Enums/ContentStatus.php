<?php

declare(strict_types=1);

namespace Domain\Content\Enums;

enum ContentStatus: string
{
    case Draft = 'draft';
    case InReview = 'in_review';
    case Approved = 'approved';
    case Published = 'published';
    case Archived = 'archived';

    public function canTransitionTo(self $next): bool
    {
        return match ($this) {
            self::Draft => in_array($next, [self::InReview]),
            self::InReview => in_array($next, [self::Approved, self::Draft]),
            self::Approved => in_array($next, [self::Published, self::Draft]),
            self::Published => in_array($next, [self::Archived, self::Draft]),
            self::Archived => in_array($next, [self::Draft]),
        };
    }
}
