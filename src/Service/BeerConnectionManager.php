<?php

namespace App\Service;

use GuzzleHttp\Client;

class BeerConnectionManager
{

    public static function getPacket()
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request(
            'GET',
            'https://api.punkapi.com/v2/beers',
            ['verify' => false] //NE FAITES PAS CA A LA MAISON LES ENFANTS* !!
        );
        $body = $res->getBody();
        $rawPacket = json_decode($body);
        return $rawPacket;
    }
}
