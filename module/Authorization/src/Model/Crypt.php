<?php

namespace Authorization\Model;

class Crypt
{
    private $SECRET_KEY = "d03la8nn4bdlc64l";

    /**
     * @param $str string строка будит зашифровона
     * @return $hash string base64 вернет hash в base64
     * */
    public function enCrypt($str, $secretKey = null)
    {
        $secretKey = ((!is_null($secretKey) ? $secretKey : $this->SECRET_KEY));
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $hash = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $secretKey, $str, MCRYPT_MODE_ECB, $iv);
        return base64_encode($hash);
    }

    /**
     * @param $str string строка будит расшифрована
     * @return $deHash расшифрованая строка
     * */
    public function deCrypt($str, $secretKey = null)
    {
        $secretKey = ((!is_null($secretKey) ? $secretKey : $this->SECRET_KEY));
        $str = base64_decode($str);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $deHash = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $secretKey, $str, MCRYPT_MODE_ECB, $iv));
        return $deHash;
    }

    /**
     * @return string md5 (token + SECRET_SALT_CRYPT_TOKEN)
     * */
    public function generateKeyHash($token,$critical = false)
    {
        $string = $token . $this->getSalt();

        if($critical){
            $string .= 'critical';
        }

        return md5($string);
    }

    /**
     * Вернет соль
     * @return string
     * */
    protected function getSalt()
    {
        return 'c67v02j52x41bw1d';
    }

}