<?php 
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Controllers\Tools\Connection;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../config/config.php';

$entitiesPath = array(__DIR__.'/Models/Entities');
$dev = true;
$config = Setup::createAnnotationMetadataConfiguration ($entitiesPath, $dev);

$entityManager = EntityManager:: create($dbParams, $config);
Connection::setConnection($entityManager);

?>