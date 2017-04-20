<?php
namespace Controllers\Tools;
define('UN_PEU_DE_SEL', '4a7wddwnw0iju1qvtptioh4tcjw9qkg05wki286nb4vpiioo7uimhjkpxp4a1bga0vb99qma0pse69fg9gxgipby49ewtqczrzy6dsp6mmugk2tjrwusicw6bg8aza2w27snp712jjraaopolp9bovob5nxorqxz3vbf95q0dm3znycquboudqcr42r1yu7rwicvkij8');
define('KEY', 'zafPRXjU');
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

    public static function createPassword($nbCaractere)
    {
        $password = "";
        for($i = 0; $i <= $nbCaractere; $i++)
        {
            $random = rand(97,122);
            $password .= chr($random);
        }
        return $password;
    }
    public static function encrypt($data) 
    {
        $data = serialize($data);
        $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, KEY ,$iv);
        $data = base64_encode(mcrypt_generic($td, '!'.$data));
        mcrypt_generic_deinit($td);
        return $data;
    }
 
    public static function decrypt($data) 
    {
        $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, KEY ,$iv);
        $data = mdecrypt_generic($td, base64_decode($data));
        mcrypt_generic_deinit($td);
     
        if (substr($data,0,1) != '!')
            return false;
     
        $data = substr($data,1,strlen($data)-1);
        return unserialize($data);
    }
}
