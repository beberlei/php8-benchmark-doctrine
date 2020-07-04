<?php

require_once __DIR__ . '/bootstrap.php';

for ($i = 0; $i < 50; $i++) {
    $author = new \App\Author("ppl $i");
    $entityManager->persist($author);
}

$entityManager->flush();
$authors = $entityManager->getRepository(\App\Author::class)->findAll();

for ($i = 0; $i < 10000; $i++) {
    $book = new \App\Book("book $i", $authors[array_rand($authors)]);
    $entityManager->persist($book);
}

$entityManager->flush();
