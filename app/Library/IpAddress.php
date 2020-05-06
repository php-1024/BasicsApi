<?php


namespace App\Library;


use App\Models\City;
use GuzzleHttp\Client;

class IpAddress
{
    /**
     * 根据ip获取用户的地址
     * @param $ip
     * @return bool
     */
    public static function address($ip)
    {
        $url = "https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query={$ip}&resource_id=6006";
        $client = new Client();
        $response = $client->get($url)->getBody()->getContents();
        $response = mb_convert_encoding($response, 'utf-8', 'GB2312');
        $re = json_decode($response, true);
        if (empty($re['data'])) {
            return false;
        } else {
            return $re['data']['0'];
        }
    }

    public static function get_city_code($ip = "")
    {
        $ip2region = new \Ip2Region();

        $info = $ip2region->btreeSearch($ip);
        $city_code = '';
        if ($info['region']) {
            $address_arr = explode('|', $info['region']);
            $city = $address_arr[3];
            $city_code = City::getValue([['name', 'like', '%' . $city . '%']], 'id');
        }
        return $city_code;
    }
}