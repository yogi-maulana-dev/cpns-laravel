<?php

namespace App\Services;

class BootstrapElementsService
{
    public static function badge(
        string $color = 'primary',
        string $type = 'a',
        ?string $child = '',
        array $attributes = []
    ): string {
        $attributes = implode(' ', array_map(fn ($key) => "$key=\"$attributes[$key]\"", array_keys($attributes)));

        if ($type == 'span') {
            return '<span '.$attributes.' class="badge text-'.$color.'-emphasis bg-'.$color.'-subtle border border-'.$color.'-subtle">'.$child.'</span>';
        }

        if ($type == 'a') {
            return '<a '.$attributes.' class="badge text-'.$color.'-emphasis bg-'.$color.'-subtle border border-'.$color.'-subtle">'.$child.'</a>';
        }

        return '';
    }
}
