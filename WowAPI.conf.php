<?php

/**
 * The config file for WowAPI class
 * @author Erwan Guillon
 * @copyright Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License (http://creativecommons.org/licenses/by-nc-sa/3.0/)
 * @package gaston-Wow-API
 * @subpackage config
 */

define('GWA_CACHE_TIME', 60*60*24);                         // Put it to 0 to desactivate cache
define('GWA_CACHE_FOLDER', dirname(__FILE__) . '/cache/');  // Your cache folder
define('GWA_KEY_PUBLIC', '');                               // Public key for the API (not tested yet)
define('GWA_KEY_PRIVATE', '');                              // Private key for the API (not tested yet)
