<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class UIHelper
{
    /**
     * Get consistent colors for categories across the app.
     */
    public static function getCategoryColor($categoryName)
    {
        $normalizedCategory = Str::of($categoryName)->ascii()->lower()->trim()->value();

        $categoryColorMap = [
            'alimentacion' => ['bg' => '#fef3c7', 'text' => '#92400e', 'border' => '#fcd34d'],
            'pasajes aereos' => ['bg' => '#e0f2fe', 'text' => '#0c4a6e', 'border' => '#7dd3fc'],
            'insumos computacionales' => ['bg' => '#ede9fe', 'text' => '#5b21b6', 'border' => '#c4b5fd'],
            'movilizacion' => ['bg' => '#dcfce7', 'text' => '#166534', 'border' => '#86efac'],
            'hospedaje' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'border' => '#fca5a5'],
            'papeleria' => ['bg' => '#ffedd5', 'text' => '#9a3412', 'border' => '#fdba74'],
            'otros' => ['bg' => '#e0e7ff', 'text' => '#3730a3', 'border' => '#a5b4fc'],
        ];

        if (isset($categoryColorMap[$normalizedCategory])) {
            return $categoryColorMap[$normalizedCategory];
        }

        $categoryPalette = [
            ['bg' => '#e0eeff', 'text' => '#0b4f86', 'border' => '#8fc0f0'],
            ['bg' => '#e9f7ef', 'text' => '#166534', 'border' => '#86efac'],
            ['bg' => '#fef3c7', 'text' => '#92400e', 'border' => '#fcd34d'],
            ['bg' => '#ede9fe', 'text' => '#5b21b6', 'border' => '#c4b5fd'],
            ['bg' => '#fee2e2', 'text' => '#991b1b', 'border' => '#fca5a5'],
            ['bg' => '#d1fae5', 'text' => '#065f46', 'border' => '#6ee7b7'],
        ];
        $paletteIndex = ((int) sprintf('%u', crc32($normalizedCategory))) % count($categoryPalette);
        return $categoryPalette[$paletteIndex];
    }
}
