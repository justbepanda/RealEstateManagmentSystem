<?php

namespace App\Orchid\Layouts\Charts;

use Orchid\Screen\Layouts\Chart;

class StatusPieChartLayout extends Chart
{
    protected $title = 'Статус помещений';
    protected $target = 'statusPie';
    protected $type = 'pie';
    protected $height = 250;
}
