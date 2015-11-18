<?php
/**
 * YiiBrandCaptcha class - wrapper for BrandCAPTCHA
 * Yii extension for BrandCAPTCHA
 * https://github.com/PontaMedia/brandcaptcha-plugin-php
 * Copyright (c) 2015 YiiBrandCaptcha
 *
 * @package YiiBrandCaptcha
 * @author Federico Nicolás Motta <fedemotta@gmail.com>
 * @copyright Copyright (c) 2015 YiiBrandCaptcha
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 */

/**
 * Include the the BrandCAPTCHA php library.
 */
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'brandcaptcha-plugin'.DIRECTORY_SEPARATOR.'brandcaptchalib.php');


class YiiBrandCaptcha {
    
    //Define the key of the Yii params for the config array
    const CONFIG_PARAMS='YiiBrandCaptcha'; 
    public $error = null;

    /**
     * Set and configure initial parameters
     */
    public function __construct()
    {
            //initialize config
            if(isset(Yii::app()->params[self::CONFIG_PARAMS])){
                $config=Yii::app()->params[self::CONFIG_PARAMS];
            }
            //set config
            $this->setConfig($config);
    }

    /**
     * Configure parameters
     * @param array $config Config parameters
     * @throws CException
     */
    private function setConfig($config)
    {
            if(!is_array($config))
                    throw new CException("Configuration options must be an array!");
            foreach($config as $key=>$val)
            {
                    $this->$key=$val;
            }
    }
    
    /**
     * Generates client side captcha
     * @return html
     */
    public function client_side(){
        //the public key
        $publickey = $this->public_key;
        return brandcaptcha_get_html($publickey, $this->error);
    }

    /**
     * Validates server side captcha
     * @return boolean
     */
    public function server_side(){
        
        $privatekey = $this->private_key;

        // the response from BrandCAPTCHA
        $resp = null;

        // was there a BrandCAPTCHA response?
        if (isset($_POST["brand_cap_answer"]) && $_POST["brand_cap_answer"]) {
            $resp = brandcaptcha_check_answer ($privatekey,
                $_SERVER["REMOTE_ADDR"],
                $_POST["brand_cap_challenge"],
                $_POST["brand_cap_answer"]);

            if ($resp->is_valid){
                // Your code here to handle a successful verification
                return true;
            }else{
                // set the error code so that we can display it
                $this->error = $resp->error;
                return false;
            }
        }
    }
        
}
