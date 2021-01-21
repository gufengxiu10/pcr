<?php

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Swlib\Saber;
use Swlib\SaberGM;
use Vectorface\Whip\Whip;

require_once "vendor/autoload.php";

// $client = new Client(['http_errors' => FALSE, 'allow_redirects' => TRUE]);
// $url = "https://1.0.0.1/dns-query?ct=application/dns-json&name=app-api.pixiv.net&type=A&do=false&cd=false";
// $r = $client->request('GET', $url);
// $json = json_decode($r->getBody(), TRUE);
// dump($client);

go(function () {
    $hash_secret = '28c1fdd170a5204386cb1313c7077b34f83e4aaf4aa829ce78c231e05b0bae2c';
    $client_id = 'MOBrBDS8blbauoSck0ZfDbtuzpyT';
    $client_secret = 'lsACyCD94FhDUtGTXi3QzcFE2uU1hqtDaKeqrdwj';
    $info = SaberGM::get('https://1.0.0.1/dns-query?ct=application/dns-json&name=app-api.pixiv.net&type=A&do=false&cd=false');
    $data = $info->getParsedJsonArray();
    $hosts = $data['Answer'][0]['data'];
    $time = gmdate('Y-m-dTH:i:s+00:00');
    $time = str_replace('GM', '', $time);
    $headers = [
        'Accept-Language' => "zh-cn",
        'User-Agent' => 'PixivAndroidApp/5.0.115 (Android 6.0; PixivBot)',
        'X-Client-Time' => $time,
        'X-Client-Hash' => md5($time . $hash_secret),
    ];
    $data['grant_type'] = 'password';
    $data['username'] = 'annghanyuu@gmail.com';
    $data['password'] = 'Freedomx102';
    $data = [
        'get_secure_url' => 1,
        //'include_policy'=>1,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
    ];
    $saber = Saber::create([
        'base_uri' => 'https://' . $hosts,
        'headers' => $headers,
        'proxy' => 'http://172.200.1.5:8087'
    ]);
    echo $saber->post('/auth/token', $data);
});
return
    dd(1);
date_default_timezone_set("Asia/Shanghai");
$jar = new \GuzzleHttp\Cookie\CookieJar();

$path_parts = pathinfo(__FILE__);
$path = $path_parts['dirname'] . '/cacert.pem';
$client = new Client(['cookies' => $jar, 'http_errors' => FALSE, 'allow_redirects' => TRUE]);
$url = "https://1.0.0.1/dns-query?ct=application/dns-json&name=app-api.pixiv.net&type=A&do=false&cd=false";
$r = $client->request('GET', $url);
$json = json_decode($r->getBody(), TRUE);
$data = $json['Answer'];
$hosts = $data[0]['data'];
// 获取token
$time = gmdate('Y-m-dTH:i:s+00:00');
$time = str_replace('GM', '', $time);
$headers = [
    'Accept-Language' => "zh-cn",
    'User-Agent' => 'PixivAndroidApp/5.0.115 (Android 6.0; PixivBot)',
    'X-Client-Time' => $time,
    'X-Client-Hash' => md5($time . $hash_secret),
];
$url = "https://oauth.secure.pixiv.net/auth/token";
$parseUrl = parse_url($url);
$data = [
    'get_secure_url' => 1,
    //'include_policy'=>1,
    'client_id' => $client_id,
    'client_secret' => $client_secret,
];
$host = $parseUrl['host'];
$headers['Host'] = $host;
$url = str_replace($host, $hosts, $url);
dump($url);
$data['grant_type'] = 'password';
$data['username'] = 'annghanyuu@gmail.com';
$data['password'] = 'Freedomx102';

$rs = $client->request("POST", $url, [
    'headers' => $headers,
    'form_params' => $data,
    // "verify"    => false,
    'proxy' => 'http://172.200.1.5:8087',
    'version' => 2
]);
dump($rs->getStatusCode());
dump($rs->getBody()->getContents());
