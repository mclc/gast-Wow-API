# Gast Wow API #

Simple class to access the armory informations

## Requirements ##

* PHP5
* JSON
* cURL

## Usage ##

simply put

        include 'WowAPI.php'

At the begining of yout file

## Configuration ##



## Examples ##

### Character ###

        $character = WowAPI::character('Gasba', 'eu', 'Medivh');
        $character = WowAPI::character('Gasba', 'eu', 'Medivh', 'all');
        $character = WowAPI::character('Gasba', 'eu', 'Medivh', 'achievements');
        $character = WowAPI::character('Gasba', 'eu', 'Medivh', array('stats', 'guild');

## Todo ##

* More tests

## License ##

No licence for the moment