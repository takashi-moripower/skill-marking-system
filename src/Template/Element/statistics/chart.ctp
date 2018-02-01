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
        'display' => false,
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
    $(function () {
        $('.btn-debug').on('click', function () {
            toggleLine(0);
        });

        function toggleLine(lineId) {
            var hidden = myChart.data.datasets[lineId]._meta[0].hidden ? false : true;
            myChart.data.datasets[lineId]._meta[0].hidden = hidden;
            myChart.update();
            return hidden;
        }

        function setLineHidden(lineId, value) {
            myChart.data.datasets[lineId]._meta[0].hidden = value;
            myChart.update();
        }

        $('.line-chart-selector').on('click', function (event) {
            var btn = $(event.currentTarget);
            
            var lineId = btn.attr('line_id');
            var hidden = btn.attr('line_hidden');
            var newHidden = !(hidden > 0);
            
            setLineHidden(lineId, newHidden);
            if( newHidden ){
                btn.attr('line_hidden', 1);
                btn.removeClass('btn-outline-dark');
                btn.addClass('btn-dark');
            }else{
                btn.attr('line_hidden', 0);
                btn.removeClass('btn-dark');
                btn.addClass('btn-outline-dark');
            }
            
            
        });

    });
</script>
