<?php

namespace App\Enums;

enum ProductStatus: string
{
    case PUBLISHED = 'published';
    case UNPUBLISHED = 'unpublished';
    case DRAFT = 'draft';
}
