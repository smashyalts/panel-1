<?php

namespace App\Filament\Resources\NodeResource\Widgets;

use App\Models\Node;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class NodeStorageChart extends ChartWidget
{
    protected static ?string $heading = 'Storage';

    protected static ?string $pollingInterval = '60s';

    public ?Model $record = null;

    protected static ?array $options = [
        'scales' => [
            'x' => [
                'grid' => [
                    'display' => false,
                ],
                'ticks' => [
                    'display' => false,
                ],
            ],
            'y' => [
                'grid' => [
                    'display' => false,
                ],
                'ticks' => [
                    'display' => false,
                ],
            ],
        ],
    ];

    protected function getData(): array
    {
        /** @var Node $node */
        $node = $this->record;

        $total = $node->statistics()['disk_total'] ?? 0;
        $used = $node->statistics()['disk_used'] ?? 0;
        $unused = $total - $used;

        return [
            'datasets' => [
                [
                    'label' => 'Data Cool',
                    'data' => [$used, $unused],
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                    ],
                ],
                // 'backgroundColor' => [],
            ],
            'labels' => ['Used', 'Unused'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
