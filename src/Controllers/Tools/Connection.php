<?php
namespace Controllers\Tools;

/**
 * Class Connection
 * Permet de conserver l'entityManager
 * @package Controllers\Tools
 */
class Connection
{
    protected static $entityManager;

    public static function getConnection()
    {
        return static::$entityManager;
    }

    public static function setConnection($em)
    {
        static::$entityManager = $em;
    }
}