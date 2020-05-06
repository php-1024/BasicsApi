<?php


namespace App\Library;


use App\Models\City;
use GuzzleHttp\Client;

class IpAddress
{
    public static function get_city_code($ip = "")
    {
        $ip2region = new \Ip2Region();

        $info      = $ip2region->btreeSearch($ip);
        $city_code = '';
        if ($info['region']) {
            $address_arr = explode('|', $info['region']);
            $city        = $address_arr[3];
            $city_code   = City::getValue([['name', 'like', '%' . $city . '%']], 'id');
        }
        return $city_code;
    }
}