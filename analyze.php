<?php

$fh = fopen("data.csv", "r");

$results = [];
while ($row = fgetcsv($fh, 4096, ";")) {
    $test = $row[0] ." - " . $row[1];
    if (!isset($results[$test])) {
        $results[$test] = [];
    }
    $results[$test][] = $row[2] * 1000;
}

foreach ($results as $test => $data) {
    echo $test ." => " . round(array_sum($data) / count($data)) . "\n";
}
