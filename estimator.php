<?php

$marginOfFailure = $argv[1] ?? 0.1;
$res = fopen("estimate.csv", "r");
$fp = fopen('result-' . time() . '.csv', 'w');
$rows = [];

while ($value = fgetcsv($res, null, ",")) {
    $estimation = 0;
    $optimistic = (int)$value[0];
    $pesimistic = (int)$value[1];
    $pesimistic_percentage = ((int)str_replace('%', '', $value[2])) / 100;

    $estimation = is_pesimistic($pesimistic_percentage) ? $pesimistic : $optimistic;
    $estimationMarginOfFailure = random_int(0, $marginOfFailure * 100) / 100;

    $payload = [
        'optimistic' => $optimistic,
        'pesimistic' => $pesimistic,
        'percentage' => $pesimistic_percentage,
        'estimation' => $estimation,
        'margin_of_failure' => $estimationMarginOfFailure,
        'final_estimation' => round($estimation * (1 + $estimationMarginOfFailure)),
    ];

    $rows[] = $payload;

    fputcsv($fp, [
        $payload['final_estimation'],
    ]);
}
fclose($fp);

var_dump($rows);

function is_pesimistic(float $percentage): bool
{
    return random_int(0, 100) <= $percentage * 100;
}