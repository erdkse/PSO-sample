<?php


function findMaxValue($x)
{
    return cos(0.5 * $x) * sin(5.5 * $x);
}

$isFirst = true;
$min = 1.0;
$max = 10.0;
$increment = 0.01;
$maxResult;

do {
    if ($isFirst) {
        $min = $min + $increment;
        $x = number_format($min, 2);
        $maxResult = findMaxValue($x) . PHP_EOL;
        $isFirst = false;
    } else {
        $min = $min + $increment;
        $x = number_format($min, 2);
        $tempResult = findMaxValue($x) . PHP_EOL;
        if ($tempResult > $maxResult) {
            $maxResult = $tempResult;
        }
    }
} while ($x < $max - $increment);

echo $maxResult;