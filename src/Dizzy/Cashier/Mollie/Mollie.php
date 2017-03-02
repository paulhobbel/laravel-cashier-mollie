<?php
/**
 * Created by PhpStorm.
 * User: paulh
 * Date: 2-3-2017
 * Time: 15:35
 */

namespace Dizzy\Cashier\Mollie;


class Mollie
{
    /**
     * @var Mollie;
     */
    public static $global;

    private $_apiKey = null;

    /**
     * Resets Mollie to defaults.
     */
    public static function reset()
    {
        self::$global = new Mollie();
    }

    /**
     * Use this function to set the apiKey.
     *
     * @param $value
     */
    public static function apiKey($value)
    {
        self::$global->_apiKey = $value;
    }

    /**
     * Creates a new Mollie_API_Client instance.
     *
     * @return \Mollie_API_Client
     */
    public static function client()
    {
        $client = new \Mollie_API_Client;
        $client->setApiKey(self::$global->_apiKey);
        return $client;
    }
}

Mollie::reset();