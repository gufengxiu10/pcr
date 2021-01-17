<?php

declare(strict_types=1);


namespace App\Api\Pixiv\Biu;

use Anng\lib\App;
use GuzzleHttp\Client;

class Rank
{

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->config = $this->config->get('api')['pixiv_biu'];
        $this->api = -$this->config['api'];
        $this->client = new Client([
            'base_uri' => $this->config['host'] . ':' . $this->config['prot'] . '/'
        ]);
    }

    /**
     * @name: 日排行榜
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-17 22:23:14
     * @return {*}
     */
    public function day()
    {
        $this->client->request("GET", $this->api['info']['rank'], [
            'query' => [
                'mode' => 'day'
            ]
        ]);
    }
}
