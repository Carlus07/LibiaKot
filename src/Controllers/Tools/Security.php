<?php
namespace Controllers\Tools;
define('UN_PEU_DE_SEL', '4a7wddwnw0iju1qvtptioh4tcjw9qkg05wki286nb4vpiioo7uimhjkpxp4a1bga0vb99qma0pse69fg9gxgipby49ewtqczrzy6dsp6mmugk2tjrwusicw6bg8aza2w27snp712jjraaopolp9bovob5nxorqxz3vbf95q0dm3znycquboudqcr42r1yu7rwicvkij8');

/**
 * Class Security
 * Rassemble les outils sécuritaires
 * @package Controllers\Tools
 */
class Security
{
    /**
     * Crypte un texte grace au md5
     * @param $text Texte à crypter
     * @return string Retourne le texte crypté
     */
    public static function cryptage($text)
    {
        return md5(UN_PEU_DE_SEL . $text);
    }

    /**
     * Génère un token aléatoire
     * @return string Token
     */
    public static function generateToken()
    {
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        return substr($token, 0, 49);
    }
}
