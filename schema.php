<?php

require_once __DIR__ . '/bootstrap.php';

$schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
$schemaTool->createSchema(
    $entityManager->getMetadataFactory()->getAllMetadata()
);
