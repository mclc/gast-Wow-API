<?php

include 'WowAPI.conf.php';


/**
 * The main class to call the Wow API
 * @package gaston-Wow-API
 */
class WowAPI
{
    /**
     * The valid regions list
     * @access private
     * @var array
     * @static
     */
    private static $_regions = array('us', 'eu', 'kr', 'tw', 'cn');
    
    /**
     * All the possible valid character fields values 
     * @access private
     * @var array
     * @static
     */
    private static $_character_fields = array(
        'guild',
        'stats',
        'talents',
        'items',
        'reputation',
        'titles',
        'professions',
        'appearence',
        'companions',
        'mounts',
        'pets',
        'achievements',
        'progression'
        );
    
    /**
     * All the possible valid guild fields values 
     * @access private
     * @var array
     * @static
     */
    private static $_guild_fields = array('members', 'achievements');
    
    /**
     * All the possible valid data ressources
     * @access private
     * @var array
     * @static
     */
    private static $_data_ressources = array(
        'races',
        'classes',
        'guildrewards',
        'guildperks',
        'item'
        );
    
    /**
     * Return character informations
     * @access public
     * @param string $character_name
     * @param string $region Must be a valid region
     * @param string $realm
     * @param string|array $options Only string 'all' is accepted, if you want to pass one or more fields, use an array instead
     * @return StdClass
     * @static
     */
    //TODO: if string, allow only one field
    public static function character($character_name, $region = 'eu', $realm = 'Medivh', $options = null)
    {
        if(!in_array($region, self::$_regions))
        {
            return;
        }
        
        $url = "http://$region.battle.net/api/wow/character/$realm/$character_name";
        
        if($options != null)
        {
            if($options == 'all')
            {
                $url .= "?fields=" . implode(',', self::$_character_fields);
            }
            else if(is_array($options))
            {
                // Clean bas field parameters
                $options = array_intersect($options, self::$_character_fields);
                $url .= "?fields=" . implode(',', $options);
            }
        }
        
        return self::_call($url);
    }
    
    /**
     * Return guild informations
     * @access public
     * @param string $guild_name
     * @param string $region Must be a valid region
     * @param string $realm
     * @param string|array $options Only string 'all' is accepted, if you want to pass one or more fields, use an array instead
     * @return StdClass
     * @static
     */
    //TODO: if string, allow only one field
    public static function guild($guild_name, $region = 'eu', $realm = 'Medivh', $options = null)
    {
        if(!in_array($region, self::$_regions))
        {
            return;
        }
        
        $url = "http://$region.battle.net/api/wow/guild/$realm/" . rawurlencode($guild_name);
        
        if($options != null)
        {
            if($options == 'all')
            {
                $url .= "?fields=" . implode(',', self::$_guild_fields);
            }
            else if(is_array($options))
            {
                // Clean bas field parameters
                $options = array_intersect($options, self::$_guild_fields);
                $url .= "?fields=" . implode(',', $options);
            }
        }
        
        return self::_call($url);
    }
    
    /**
     * Return realm informations
     * @access public
     * @param string $region Must be a valid region
     * @param string|array $realm you can either pass one realm as string or multiple in an array
     * @return StdClass
     * @static
     */
    public static function realm($region = 'eu', $realm = 'Medivh')
    {
        if(!in_array($region, self::$_regions))
        {
            return;
        }
        
        $url = "http://$region.battle.net/api/wow/realm/status";
        
        if(is_string($realm))
        {
            $url .= "?realms=" . rawurlencode($realm);
        }
        else if(is_array($realm))
        {
            $url .= "?realms=" . rawurlencode(implode(',', $realm));
        }
        else
        {
            return;
        }
        
        return self::_call($url);
    }
    
    /**
     * Return ressources data
     * @access public
     * @param string $data_type 
     * @param int $item_id Usefull only with $data_type = 'item'
     * @return StdClass
     * @static
     */
    public static function data($data_type, $item_id = null)
    {
        if(!in_array($data_type, self::$_data_ressources))
        {
            return;
        }
        
        $url = "http://eu.battle.net/api/wow/data/";
        
        switch($data_type)
        {
            case 'races' : $url .= 'character/races'; break;
            case 'classes' : $url .= 'character/classes'; break;
            case 'guildrewards' : $url .= 'guild/rewards'; break;
            case 'guildperks' : $url .= 'guild/perks'; break;
            //TODO: Try to see if there is a problem with the API with the data/item/item_id function
            case 'item' :
                if(is_int($item_id)) $url .= 'item/' . $item_id;
                else return;
                break;
            default: return; break;
        }
        
        return self::_call($url);
    }
    
    /**
     * Manage the API call
     * @access private
     * @param string $url
     * @return StdClass
     * @static
     */
    private static function _call($url)
    {
        //TODO: Implement cache function
        $curl = curl_init();
        
        //TODO: Check the API key if exists and pass it in headers https://github.com/Blizzard/BlizzardSDK-PHP/blob/master/blizzard/api/ApiAbstract.php line 341
        $headers = array();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPGET => true,
            CURLOPT_HTTPAUTH => CURLAUTH_ANY,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'gast-wow-api'
        ));
        
        $request = curl_exec($curl);
        $headers = curl_getinfo($curl);
        
        if($headers['http_code'] != 404)
            $response = json_decode($request);
        else $response = null;

        curl_close($curl);
        
        return $response;
    }
}
