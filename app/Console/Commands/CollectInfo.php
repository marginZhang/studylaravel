<?php

/**
 * Created by PhpStorm.
 * User: zhangyang
 * Date: 2019/2/28
 * Time: 14:01
 */

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CollectInfo extends Command
{
    protected $signature = 'collect:info';
    protected $description = 'description';

    /**
     * 初始化代码写到这里，也没什么用
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 发邮件功能
     * @author：zuocongbing
     * @date  ：2017-10-17 15:59
     * @return int
     */
    public function handle()
    {
        echo "start\n";
        $url = "https://www.guahao.com/expert/new/shiftcase/?expertId=74a96953-2aaf-4be0-be33-63c25d9bf6aa000&hospDeptId=4fe6f974-e0b5-4405-9b3e-c3c01b0d6936000&hospId=5f97d681-0528-40ac-8e43-a4db5cac6c7f000&_=1551836325711";
        $cookie = '_sid_=1551230786858018725013182; _fp_code_=ced6fc3fb789b0407463bdd1ecbbe3b5; _fm_code=e3Y6ICIyLjUuMCIsIG9zOiAid2ViIiwgczogMTk5LCBlOiAianMgbm90IGRvd25sb2FkIn0%3D; searchHistory=%E8%BD%A6%E9%9C%9E%E9%9D%99%2C%7C%2Cclear; _sh_ssid_=1551836021498; _e_m=1551836021503; monitor_sid=4; mst=1551836022194; _ipgeo=province%3A%E4%B8%8A%E6%B5%B7%7Ccity%3A%E4%B8%8D%E9%99%90; _ci_=QMfhgyhrSCPUdSmHg/M3JJWymcZ/xFZ/fvJXnC7abCqxuL+HIIiQ7uBcEJUQqG+l; __i__=FdWO6KWUoLAaQ7msSRWQcwJ9NMq6C71PLRUFfbQGfUA=; __uiu__=ONy0dwnZrEIZeH+ksMxBEDjf4l2Ngffuc4ht83BYQNBnANO+QoSImw==; __usx__=skHjkgoN7rkgVPdmplvwB8dcodDi+nlDPoSllUWBFCg=; __up__=uVEmQY53bgjzKIzK23td+XKybNrRtV6HGpTs+wgZvPU=; _exp_=++la7GmZr0tQwqFoJOi1bvOmyp2eYXdn8O5pDUn8nnw=; __p__=L/TjXBewPE165wJHe+A1TwCnvCf2bahuv8QPEIdb85LRyl+yQFFAKQ==; __uli__=eQw4yFLlQgm446MwsE5yxL4CtF81GBLKSK7p+SJSgu5IkZ/1cfhZGOwWiVgMkmdzzA2m+rc54uk=; __un__=FticQKb0AShULZlF5FsTPgRnVsypwSVDwBtmAcHixBTFHtDxcUfW8sZm0KoGL5WB; __wyt__=!PDOeLVHHoefTHSU9I3jJ_0QcjFpo9qoQ91Jn3G7iRK5WSilNSrxf4n6j7pqiPIC6hb9SCo1spws59jt730u-yZu2sJNxsg2lK5VuXSvWIjYb9XUMJG-6ChkCHOE2AKqRBS2XUPmuj1lBAnpLoBQQR0g6eFhTuzyigxULMgxlMGNLU; __rf__=iwVmE9s03qvbIh1EEJvrnRbTMklXTfFiv7q1uf7r4ctijPUOzhwnq4jNzZWZhFmL+l1YkqY4cHH6L9SV+qlpcpHrzA+Joy1wetuxxmWp+G5G93JpAFM2nwVkqha9rl6V; monitor_seq=13; mlt=1551836325541';
        $res = $this->curl_request($url, '', $cookie);
        $res = json_decode($res, true);
        $info = $res['data']['shiftSchedule'];
        $msg = '';
        $mark = 0;
        $subject = iconv("GBK", "UTF-8//IGNORE", "车医生没有班次");
        $to = array('822326559@qq.com');
        $date = array("2019-03-20", "2019-03-27");
        if (isset($info)) {
            foreach ($info as $index => $item) {
                $msg .= $item['date'] . $item['extraStateDesc'] . "\n";
                if (in_array($item['date'], $date) && $item['extraState'] == 1) {
                    $mark++;
                }
            }
            $content = $msg;
            if ($mark > 0) {
                $subject = iconv("GBK", "UTF-8//IGNORE", "可以约车医生了");
                array_push($to, "332926195@qq.com");
            }
        }
        Mail::Raw($content, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
        echo $mark . "\n";
        echo "end\n";
    }


    /**
     * 请求远程服务器以获取响应
     * @param string $url 资源地址
     * @param string $post 请求参数
     * @param string $cookie 请求方式
     * @param int $returnCookie 超时时间（秒）
     * @return mixed
     */
    private function curl_request($url, $post = '', $cookie = '', $returnCookie = 0)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        if ($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if ($returnCookie) {
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie'] = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        } else {
            return $data;
        }
    }
}
