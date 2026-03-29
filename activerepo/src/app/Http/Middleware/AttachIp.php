<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class AttachIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function getUserIp()
    {
        //①プロキシ（VPNやCDNなど）を通過した場合に割り出す処理
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipList = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach($ipList as $ip){
                $ip = trim($ip);
                if(filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_NO_PRIV_RANGE|FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        //①終了

        //現在は滅多に使用されない処理だがIPを割り出す処理
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }
    public function getgenerateID($ip)
    {
        $date = date('Y-m-d');

        if(Cache::has("ip_id_$ip")){
            return Cache::get("ip_id_$ip");
        }

        $hash = sha1($ip . $date . uniqid());
        //return substr($hash,0,8);
        $id = substr($hash,0,8);
        //キャッシュを10分間は残しておく
        Cache::put("ip_id_$ip", $id, now()->addMinutes(10));
        return $id;
    }
    //ipアドレスからキャリアを割り出す
    public function gethostName($ip)
    {
        return gethostbyaddr($ip);
    }


    public function getEndidentifer($idName)
    {
        if(strpos($idName,'softbank.ne.jp')!== false || strpos($idName,'bbtec.net')!== false){
            return 'Sr';
        }
        if(strpos($idName,'au-net.ne.jp')!==false){
            return 'Sa';
        }
        if(strpos($idName,'docomo.ne.jp')!==false){
            return 'Sd';
        }
        if(strpos($idName,'rakuten.ne.jp')!==false){
            return'Ra';
        }
        if(strpos($idName,'ocn.ne.jp')!==false){
            return'Oc';
        }else{
            return '00';
        }
    }


    public function handle(Request $request, Closure $next): Response
    {
        //ここで原始のIPアドレスを取得
        $ipAddress = $this->getuserIp();
        //IDを生成する
        $getgenerateID = $this->getgenerateID($ipAddress);

        //通信会社を判別する
        $gethostName = $this->gethostName($ipAddress);
        //IDの末尾部分を生成
        $getEndidentifer = $this->getEndidentifer($gethostName);

        //生成したID
        $GenerateID = $getgenerateID.$getEndidentifer;

        //middlewareからControllerへの値の受け渡し
        $request->merge(['GenerateID'=> $GenerateID]);
        return $next($request);
    }
}
