<?php
namespace Controllers\Tools;

use Models\Session;
use Models\Entities\User;

/**
 * Class Permission
 * Permet de gérer les rôles d'un utilisateur.
 * @package Controllers\Tools
 */
class Permission
{
	/**
     * Enregistre en session le rôle de l'utilisateur.
     * @param $role Rôle de l'utilisateur.
     */
    private static function storeRole($role)
    {
        Session::set('Role', $role);
    }

    /**
     * Récupération le role d'un utilisateur.
     * @param int $user_id ID de l'utilisateur.
     * @return int role.
     */
    public static function getRole($user_id = 0)
    {

        $entityManager = Connection::getConnection();
        $userRepo = $entityManager->getRepository('User');
        $user = $userRepo->find($user_id);
        return (!empty($user)) ? $user->getIdRole()->getId() : 1;
    }

    /**
     * Test si le rôle est déjà chargé en session.
     * @return bool True si le rôle existe.
     */
    public static function exist()
    {
        return Session::exist('Role');
    }

    /**
     * Rafraichis le role d'un utilisateur.
     * @param int $user_id Id de l'utilisateur.
     */
    public static function refresh($user_id = 0)
    {
        $role = static::getRole($user_id);
        static::storeRole($role);
    }
}