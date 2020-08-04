<?php
/**
 * Encrypt and Decrypt
 *
 * @author Sergio Casizzone <sergio.casizzone@gmail.com>
 * @link http://sergiocasizzone.it
 *
 */

class crypt {
    private   $key, $iv;

    /**
     * funzione che crypta un testo
     * @param $text il testo da criptare
     * @return testo criptato
     */
    public static function Encrypt($text) {
        return strtr(self::enc($text,  hash( 'sha256', self::secretFromFile('secret_key') )), '', '');
    }

    /**
     * funzione che decrypta un testo
     * @param $text il testo da decriptare
     * @return testo decriptato
     */
    public static function Decrypt($text) {
        return strtr(self::dec($text,  hash( 'sha256', self::secretFromFile('secret_key') )), '', '');
    }

    /**
     * Metodi privati della classe
     */
    private static function enc($text, $key) {
        return base64_encode( openssl_encrypt( $text, self::secretFromFile('encrypt_method'), $key, 0, substr( hash( 'sha256', self::secretFromFile('secret_iv') ), 0, 16 ) ) );
    }

    private static function dec($text, $key) {
        return openssl_decrypt( base64_decode( $text ), self::secretFromFile('encrypt_method'), $key, 0, substr( hash( 'sha256', self::secretFromFile('secret_iv') ), 0, 16 ) );
    }


    /**
     * questa funzione prova a caricare il secret_key e secret_iv dalla directory corrente in un file
     * @param $key la chiave da leggere
     * @return restituisce la chiave letta
     */
    private static function secretFromFile($key)
    {
        $file = dirname(__FILE__).'/encrypt.json';

        if (file_exists($file) === false) {
            echo "Unable to load config from: " . $file . PHP_EOL;
            echo "Detected no SECRET KEY or SECRET IV, all signed requests will fail" . PHP_EOL;
            return;
        }
        $contents = CJSON::decode(file_get_contents($file),true);
        $return = isset($contents[$key]) ? $contents[$key] : "";

        return $return;
    }
}



?>
