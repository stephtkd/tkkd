<?php


namespace App\Classe;


class HelloAsso
{
    private static $_instance;

    public $clientId = "";                                // Votre API client id
    public $clientSecret = "";                            // Votre API client secret
    public $organismSlug = "";                            // Slug de votre association
    public $baseUrl = "https://127.0.0.1:8000";           // A modifier en production
    public $returnUrl = "https://127.0.0.1:8000/return";  // A modifier en production

    private function __construct()
    { }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new HelloAsso();
        }

        return self::$_instance;
    }

}