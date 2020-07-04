<?php

require_once __DIR__ . '/bootstrap.php';

$start = microtime(True);
$query = $entityManager->createQuery("SELECT b, a FROM App\\Book b INNER JOIN b.author a WHERE b.id > 0");
$books = $query->getResult();

$byAuthor = [];
foreach ($books as $book) {
    $byAuthor[$book->getAuthor()->getId()][] = $book;
}

echo count($byAuthor) . "\n";
$stop = microtime(true) - $start;

//var_dump(opcache_get_status(false)["jit"] ?? null);
echo number_format($stop, 6) . "\n";
