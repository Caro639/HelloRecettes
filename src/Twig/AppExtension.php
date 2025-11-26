<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('minutes_to_hour', [$this, 'minutesToHourFilter']),
        ];
    }

    public function minutesToHourFilter(int $value): string
    {
        if ($value < 60) {
            return "{$value}mn";
        }

        $hours = (int) \floor($value / 60);
        $minutes = $value % 60;

        if ($minutes < 10) {
            $minutes = "0$minutes";
        }

        return \sprintf('%02dh%02d', $hours, $minutes);
    }
}