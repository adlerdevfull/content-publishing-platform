<?php

declare(strict_types=1);

namespace Domain\Content\Enums;

enum Visibility: string
{
    case Public = 'public';
    case Restricted = 'restricted'; // registered users only
    case Private = 'private';       // author + admins only
}
