<?php

require_once __DIR__ . '/bootstrap.php';

$start = microtime(true);
$query = $entityManager->createQuery("SELECT b, a FROM App\\Book b INNER JOIN b.author a WHERE b.id > 0");
$books = $query->getResult();

$byAuthor = [];
foreach ($books as $book) {
    $byAuthor[$book->getAuthor()->getId()][] = $book;
}

echo count($byAuthor) . "\n";
$stop = microtime(true) - $start;

$number = number_format($stop, 6);
echo $number;
file_put_contents("data.csv", ini_get("opcache.jit_buffer_size") . ";" . ini_get("opcache.jit") . ";" . $number . "\n", FILE_APPEND);
