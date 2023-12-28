<?php

namespace App\Application\Presentation;

enum PaginatedResponseKeyEnum: string
{
    case Data = 'data';
    case Page = 'page';
    case Size = 'size';
    case Total = 'total';
}
