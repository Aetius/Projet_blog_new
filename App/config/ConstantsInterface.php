<?php

namespace App\Config;

/**
 *Define all environment variable.
 *You have to define the constants before using the functions.
 */
interface ConstantsInterface
{

    /**
     * CONST = string
     * @return self::DOCUMENT_ROUTE;
     */
    public static function getDocumentRoute();

        /**
         * CONST = string
         *@return self::MAIN_URL;
         */
    public static function getMainUrl();

        /**
         * CONST = string
         *@return self::DSN;
         */
    public static function getDsn();

        /**
         * CONST = string
         *@return self::USER_NAME_DB;
         */
    public static function getUserNameDb();

        /**
         * CONST = string
         *@return self::PASSWORD_DB;
         */
    public static function getPasswordDb();

        /**
         * CONST = string
         *@return self::MAIL_TO;
         */
    public static function getMailTo();

        /**
         * CONST = string
         *@ return self::MAIL_FROM;
         */
    public static function getMailFrom();

        /**
         * CONST = integer
         *@ return self::COOKIE_LIFE_TIME;
         */
    public static function getCookieLifeTime();


}