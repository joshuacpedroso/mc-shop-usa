<?php

$file = "counter.txt";

if (!isset($_COOKIE["mcshop_visited"])) {

    $count = file_exists($file)
        ? (int)file_get_contents($file)
        : 0;

    $count++;

    file_put_contents($file, $count);

    setcookie(
        "mcshop_visited",
        "true",
        time() + (86400 * 365)
    );
}

$total = file_exists($file)
    ? (int)file_get_contents($file)
    : 0;

echo json_encode([
    "total" => $total
]);