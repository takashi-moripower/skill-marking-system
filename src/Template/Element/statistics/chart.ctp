<?php

use Cake\Utility\Hash;
use App\Utility\Statistics;
use App\Utility\Color;
?>
<?php $this->append('script', $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js')); ?>
<canvas id="myChart" width="400" height="200"></canvas>

<?php
$datasets = [];

$i = 0;

foreach ($skills as $skill) {
    $H = $i * 360 / $skills->count();
    $color = Color::hsv($H, 1, 1);
    $R = $color->r;
    $B = $color->b;
    $G = $color->g;

    $i++;

    $datasets[] = [
        'label' => $skill->label,
        'data' => [
            $skill->count_1,
            $skill->count_2,
            $skill->count_3,
            $skill->count_4,
            $skill->count_5,
        ],
        'backgroundColor' => "rgba({$R},{$B},{$G},.1)",
        'borderColor' => "rgba({$R},{$B},{$G},1)",
    ];
}

$datasets_json = json_encode($datasets);
?>
<script>
    var datasets = JSON.parse('<?= $datasets_json ?>');

    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [1, 2, 3, 4, 5],
            datasets: datasets
        },
        options: {
            scales: {
                yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
            }
        }
    });
</script>
