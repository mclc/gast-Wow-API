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
     * @param string|array $options Enter 'all' to have all options available or an array for a selection of multiple optional fields
     * @return StdClass
     * @static
     */
    public static function character($character_name, $region = 'eu', $realm = 'Medivh', $options = null)
    {
        if(!in_array($region, self::$_regions))
        {
            throw new Exception('The region ' . $region . ' does not exists.');
        }
        
        $url = "http://$region.battle.net/api/wow/character/$realm/$character_name";
        
        if($options != null)
        {
            if(is_string($options))
            {
                if($options == 'all')
                {
                    $url .= '?fields=' . implode(',', self::$_character_fields);
                }
                else if(in_array($options, self::$_character_fields))
                {
                    $url .= '?fields=' . $options;
                }
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
     * @param string|array $options Enter 'all' to have all options available or an array for a selection of multiple optional fields
     * @return StdClass
     * @static
     */
    public static function guild($guild_name, $region = 'eu', $realm = 'Medivh', $options = null)
    {
        if(!in_array($region, self::$_regions))
        {
            throw new Exception('The region ' . $region . ' does not exists.');
        }
        
        $url = "http://$region.battle.net/api/wow/guild/$realm/" . rawurlencode($guild_name);
        
        if($options != null)
        {
            if(is_string($options))
            {
                if($options == 'all')
                {
                    $url .= '?fields=' . implode(',', self::$_guild_fields);
                }
                else if(in_array($options, self::$_guild_fields))
                {
                    $url .= '?fields=' . $options;
                }
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
            throw new Exception('The region ' . $region . ' does not exists.');
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
            default: throw new Exception('You must choose a data_type'); break;
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
        $cache_file = GWA_CACHE_FOLDER . md5($url);
        $timedif = @(time() - filemtime($cache_file));
        
        if (file_exists($cache_file) && $timedif < GWA_CACHE_TIME)
        {
            return json_decode(file_get_contents($cache_file));
        }
        else
        {
            $curl = curl_init();
        
            //TODO: Test with an API key but where to find it ;-)
            $headers = array();
            if(GWA_KEY_PRIVATE != '' AND GWA_KEY_PUBLIC != '');
            {
                $date = date(DATE_RFC2822);
                $headers = array(
                    'Date: '. $date,
                    'Authorization: BNET '. GWA_KEY_PUBLIC .':'. base64_encode(hash_hmac('sha1', "GET\n{$date}\n{$url}\n", GWA_KEY_PRIVATE, true))
                );
            }

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
                $response = $request;
            else $response = null;

            curl_close($curl);
            
            if( $response !== null )
            {
                if ($f = fopen($cache_file, 'w'))
                {
                    fwrite ($f, $response, strlen($response));
                    fclose($f);
                }
            }
            return json_decode($response);
        }
    }
}
