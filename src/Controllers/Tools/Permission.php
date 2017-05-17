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
        $user = $entityManager->getRepository('User')->find($user_id);
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

    public static function allowed($address, $type)
    {
        if (($type == "p") || ($type == "h"))
        {
            $entityManager = Connection::getConnection();
            $role = $entityManager->getRepository('Permission')->find(Session::get('Role'));
            $permission = $entityManager->getRepository('Permission')->findBy(array('idRole' => $role, 'url' => $address));
            return (isset($permission[0])) ? true : false;
        }
        else return true;
    }
}