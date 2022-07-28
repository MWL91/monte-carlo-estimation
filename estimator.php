<?php

$marginOfFailure = $argv[1] ?? 0.1;
$tries = $argv[2] ?? 10000;

function estmiate(array $values, float $marginOfFailure): array
{
    $rows = [];
    $summary = 0;

    foreach ($values as $value) {

        $keyBase = random_int(1, count($value) / 3) - 1;

        $estimation = 0;
        $optimistic = (int)$value[$keyBase + 0];
        $pesimistic = (int)$value[$keyBase + 1];
        $pesimistic_percentage = ((int)str_replace('%', '', $value[$keyBase + 2])) / 100;

        $estimation = is_pesimistic($pesimistic_percentage) ? $pesimistic : $optimistic;
        $estimationMarginOfFailure = random_int(0, $marginOfFailure * 100) / 100;
        $finalEstimation = round($estimation * (1 + $estimationMarginOfFailure));
        $summary += $finalEstimation;

        $payload = [
            'optimistic' => $optimistic,
            'pesimistic' => $pesimistic,
            'percentage' => $pesimistic_percentage,
            'estimation' => $estimation,
            'margin_of_failure' => $estimationMarginOfFailure,
            'final_estimation' => $finalEstimation,
            'summary' => $summary,
        ];

        $rows[] = $payload;
    }
    return $rows;
}


$res = fopen("estimate.csv", "r");
$values = [];
while ($value = fgetcsv($res, null, ",")) {
    $values[] = $value;
}

if (!is_dir('results') && !mkdir('results')) {
    throw new \RuntimeException(sprintf('Directory "%s" was not created', 'result'));
}
$fp = fopen('results/' . time() . '.csv', 'w');
$finalResults = [];
for ($i = 1; $i <= $tries; $i++) {
    $results = estmiate($values, $marginOfFailure);
    foreach ($results as $k => $result) {
        $finalResults[$k] = $result['final_estimation'] + ($finalResults[$k] ?? 0);
    }

    echo "Total in try $i: " . (array_sum($finalResults) / $i) . PHP_EOL;
}

$totalTime = 0;
foreach ($finalResults as $k => $result) {
    $calculation = ceil($result / $tries);
    fputcsv($fp, [$calculation]);
    $totalTime += $calculation;
}
fclose($fp);


echo 'Total time: ' . $totalTime . 'h';

function is_pesimistic(float $percentage): bool
{
    return random_int(0, 100) <= $percentage * 100;
}