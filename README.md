# Gast Wow API #

Simple class to access the armory informations

## Requirements ##

* PHP5
* JSON
* cURL

## Installation ##

Include the WowAPI.php file in your script like :

        include 'WowAPI.php'

## Configuration ##

Open WowAPI.conf.php file and edit it

        define('GWA_CACHE_TIME', 60*60*24);                         // Put it to 0 to desactivate cache
        define('GWA_CACHE_FOLDER', dirname(__FILE__) . '/cache/');  // Your cache folder
        define('GWA_KEY_PUBLIC', '');                               // Public key for the API (not tested yet)
        define('GWA_KEY_PRIVATE', '');                              // Private key for the API (not tested yet)

## Examples ##

Each function returns a stdClass object directly converted from json with json_decode function

### Character ###

        $character = WowAPI::character('Gasba', 'eu', 'Medivh');
        $character = WowAPI::character('Gasba', 'eu', 'Medivh', 'all');
        $character = WowAPI::character('Gasba', 'eu', 'Medivh', 'achievements');
        $character = WowAPI::character('Gasba', 'eu', 'Medivh', array('stats', 'guild');

### Guild ###

        $guild = WowAPI::guild('La Waagh Retrouvée', 'eu', 'Medivh');
        $guild = WowAPI::guild('La Waagh Retrouvée', 'eu', 'Medivh', 'all');
        $guild = WowAPI::guild('La Waagh Retrouvée', 'eu', 'Medivh', 'members');
        $guild = WowAPI::guild('La Waagh Retrouvée', 'eu', 'Medivh', array('members', 'achievements');

### Realm ###

        $realm  = WowAPI::realm('eu', 'Medivh');
        $realms = WowAPI::realm('eu', array('Medivh', 'Archimonde');

### Data ressources ###

        $races          = WowAPI::data('races');
        $classes        = WowAPI::data('classes');
        $guild_rewards  = WowAPI::data('guildrewards');
        $guild_perks    = WowAPI::data('guildperks');
        $item           = WowAPI::data('item', 48652); // Not working yet du to blizzard service problem

## Todo ##

* More tests

## License ##

This class is licensed under a Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License (http://creativecommons.org/licenses/by-nc-sa/3.0/).