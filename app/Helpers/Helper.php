<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Redis;
use Predis\Autoloader;
use Predis\Client;

/**
 * Created by PhpStorm.
 * User: areej
 * Date: 03/06/21
 * Time: 07:30 ص
 */
class Helper
{
    public static function notifications()
    {
        Autoloader::register();
        try {
            $redis = new Client();
            $redis = new Client(array(
                "scheme" => "tcp",
                "host" => "127.0.0.1"));
        } catch (Exception $e) {
            echo "Couldn't connect to Redis";
            echo $e->getMessage();
        }
        $list = $redis->keys("*");
//        dd($list) ;
//        dd( $redis->get('laravel_database_company')) ;
        $nots = [];
        foreach ($list as $item) {
            $value = $redis->get($item);
            $nots [$item] = $value;
        }
        return $nots  ;

    }
}