<?php

namespace App\Orchid\Layouts\Charts;

use Orchid\Screen\Layouts\Chart;

/**
 * Чарт для продаж
 */
class SalesChartLayout extends Chart
{
    protected $title = 'Динамика продаж помещений';
    protected $target = 'salesChart';
    protected $type = 'bar';
    protected $height = 250;
}
