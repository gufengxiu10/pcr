<?php

declare(strict_types=1);

namespace app\api\music\son\netease;

use app\api\music\encrypt\Netease;
use app\api\music\request\Netease as RequestNetease;
use GuzzleHttp\Client;
use Swlib\Saber;
use Swlib\Saber\Response;

class Login
{
    const PHONE_URL = '/login/cellphone';

    /**
     * @name: 邮箱登录
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-22 10:21:25
     * @return {*}
     */
    public function email()
    {
        //  '{"loginType":1,"code":200,"account":{"id":79237283,"userName":"1_13672666381","type":1,"status":0,"whitelistAuthority":0,"createTime":1436391126202,"salt":"[B@5e619469","tokenVersion":2,"ban":0,"baoyueVersion":1,"donateVersion":0,"vipType":11,"viptypeVersion":1612484577896,"anonimousUser":false},"token":"cb9827bcaf1f38d7618b9b2634ca3d907dd5432c16d1b0edd77b8dde887f7d519cb4377b2d7ba249","profile":{"userId":79237283,"backgroundImgIdStr":"109951164545750223","avatarImgIdStr":"3245758329051478","userType":0,"followed":false,"backgroundUrl":"https://p1.music.126.net/X4KpgEZYGH7fDolYOopKqg==/109951164545750223.jpg","detailDescription":"","vipType":11,"gender":0,"accountStatus":0,"avatarImgId":3245758329051478,"nickname":"Cry_蝉","birthday":741375749056,"city":440400,"backgroundImgId":109951164545750223,"avatarUrl":"http://p4.music.126.net/0pqDGrf8kAzTOVQC-Wt98w==/3245758329051478.jpg","province":440000,"defaultAvatar":false,"djStatus":0,"expertTags":null,"authStatus":0,"mutual":false,"remarkName":null,"experts":{},"description":"","signature":"","authority":0,"followeds":6,"follows":36,"eventCount":4,"avatarDetail":null,"playlistCount":6,"playlistBeSubscribedCount":1},"bindings":[{"url":"","userId":79237283,"refreshTime":1436391169,"tokenJsonStr":"{\"countrycode\":\"\",\"cellphone\":\"13672666381\",\"hasPassword\":true}","expiresIn":2147483647,"bindingTime":1436391169222,"expired":false,"id":37164179,"type":1},{"url":"http://t.qq.com/Freedomx102","userId":79237283,"refreshTime":1436391126,"tokenJsonStr":"{\"openkey\":\"6D32EE595DE2870EA8E31F93508832A9\",\"nick\":\"正義\",\"name\":\"Freedomx102\",\"openid\":\"8704ff9080585e751784a63e9cfe9117\",\"expires_in\":8035200,\"refresh_token\":\"c7937a67286b4f5673c7cf3a1d51c753\",\"access_token\":\"6d6b84433bcecebcfe403bf4d016cb7e\",\"openId\":\"8704FF9080585E751784A63E9CFE9117\"}","expiresIn":8035200,"bindingTime":1436391126207,"expired":true,"id":37165155,"type":6},{"url":"","userId":79237283,"refreshTime":1595731082,"tokenJsonStr":"{\"access_token\":\"44D68E1ED5B1F87721BFD44CFEBD505B\",\"refresh_token\":\"918BA5E09CBB6801ED9155131001870F\",\"unionid\":\"UID_FDE41C951D606B82CF2D5C1C6AB0AE3F\",\"openid\":\"8530E5E32E9C57387C646ABD6673A2E7\",\"nickname\":\"蝉月\",\"expires_in\":7776000}","expiresIn":7776000,"bindingTime":1564297167582,"expired":true,"id":6922049881,"type":5},{"url":"","userId":79237283,"refreshTime":1564297184,"tokenJsonStr":"{\"access_token\":\"23_GixD8BCN17WUzgBle-BNuROA1mnZToWiqlBQkeUZQ6xk4-xBaAdeO2BCHCd0K7ics3rzqCkkeXK-4RKLSHa5vWBkbgnIbVDEEG6nddbHHhw\",\"expires_in\":7200,\"refresh_token\":\"23_w5nU0dqJ-GCYMph1gJGbQcpQztM2w9-GuI4r5XgVdQjsQr-TiCzxa-xVCO9keravXDisVNAeEQY-nBHAYFWcmhvjNxXV6z-IAN2WJGCjnC4\",\"openid\":\"o5xcyt4j86AfqgqDLjynsz-qGDoU\",\"scope\":\"snsapi_login\",\"unionid\":\"oZoefuMi6BOdOrN4DJbKhQfBZ8WI\",\"nickname\":\"蝉月\"}","expiresIn":7200,"bindingTime":1564297184401,"expired":true,"id":6922040270,"type":10}]}'

        $res = RequestNetease::init()->send(self::PHONE_URL, 'POST', [
            'data'  => [
                'phone' => '13672666381',
                'countrycode' => '86',
                'password' => md5('gufengxiu10'),
                'rememberLogin' => 'true',
                'csrf_token' => ''
            ]
        ]);

        // Swlib\Saber\Response
        if ($res instanceof Response) {
        }
        return (string)$res->getBody();

        $client = new Client();
        $res = $client->post(self::PHONE_URL, [
            'form_params' => Netease::init()->aescbc([
                'phone' => '1367266381',
                'countrycode' => 86,
                'password' => bin2hex(md5('gufengxiu10')),
                'rememberLogin' => true,
                'csrf_token' => ''
            ]),
            'proxy' => 'http://192.168.1.8:8866',
            'headers' => [
                'Referer'         => 'https://music.163.com/',
                'Cookie'          => 'os=pc',
                'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.182 Safari/537.36 Edg/88.0.705.74',
                'X-Real-IP'       => long2ip(mt_rand(1884815360, 1884890111)),
                // 'Accept'          => '*/*',
                // 'Accept-Language' => 'zh-CN,zh;q=0.8,gl;q=0.6,zh-TW;q=0.4',
                // 'Connection'      => 'keep-alive',
                'Content-Type'    => 'application/x-www-form-urlencoded'
            ],
        ]);


        dump($res);
        dump((string)$res->getBody());
    }
}
