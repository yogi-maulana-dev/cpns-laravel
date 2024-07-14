<?php

namespace App\Traits;

trait DefaultSort
{
    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';
}
