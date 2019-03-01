<?php
/**
 * Created by PhpStorm.
 * User: zhangyang
 * Date: 2019/2/28
 * Time: 12:50
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{
    private $appID = 'wxaffbaacbba877dec';
    private $appSecret = '2d4f9c15502c63325e3c84685173d333';
    /**
     * 为指定用户显示详情
     * @return resource
     */
    public function index()
    {
        $url = "https://www.guahao.com/expert/new/shiftcase/?expertId=74a96953-2aaf-4be0-be33-63c25d9bf6aa000&hospDeptId=43d801b5-8bf7-4b9d-93c7-4f0cc354c824000&hospId=5f97d681-0528-40ac-8e43-a4db5cac6c7f000&_=1551240184822";
        $cookie = '_sid_=1551230786858018725013182; _e_m=1551230786868; monitor_sid=1; mst=1551230787758; _fp_code_=ced6fc3fb789b0407463bdd1ecbbe3b5; _fm_code=e3Y6ICIyLjUuMCIsIG9zOiAid2ViIiwgczogMTk5LCBlOiAianMgbm90IGRvd25sb2FkIn0%3D; _ci_=1+a0EpiqVtj9cfQhgFLelZvl2OfWJtsve6Ib8VHelMtaST7odHGIyUV9hi37s/ey; __i__=ueg1oMqZVjym7WMexn0WIvtASDq/UdojIUH9drQOZuA=; __uiu__=Z35Cxt4X3PY3YK+AUbpSIAq0CHuXoj4lk155hFMJ/I3P8R/9xbko0A==; __usx__=3B5Fx7NR9J9b1shCz836qOjmD2ZBLa6Dsavvkeq2GRU=; __up__=1a5P7icd01ZNKIyG8MnBPs+EFQF6yIyDmAYVu8H0PgA=; _exp_=gukd3T5fyRE4d9QMlqlDWSH3fVbk42NZmnCfSmmqQz0=; __p__=0YRn19Pp3mHXFoNgfc3hGgPYxb8gz3lZ20Qug9GrkCPKcOuT1gxMmA==; __uli__=p+JiDO+FAukGkrbvjUQZt0qQlPVbGo4iIMVwMH1j0nm7HeWmYi863v29y1pbzXCJlV/vFcW55ps=; __un__=DhF5YoihWeDz9ppuyqlk6Yce3trenLfbDQvTfHxIv1xOUXBedqIz+u/IufJIT3Pw; __wyt__=!PqKRPWocxAd-TDdNusfTZ7H8jFpo91oQ9DJn3G7iRK5WRyqRopEmFbF9aqaeAl6T088bUTe8acYIl5g5yygmGIZj4aNqz49ZLFM5CGi7LhA0BXUMJG-6ChkCHOE2AKqRBS2XUPmuj1lBAnpLoBQQR0gwZgzITm9jX5qr-K4UJscow; __rf__=MM6HkL5iUAp4uI0zmtFE3rSvEO3SiD8wRunX1BFhXvZCNtGitM7mAccbv5X1pjmyLVBMz7zEBz0eZDJyECstKQ==; monitor_seq=5; mlt=1551230821929';
        $res = $this->curl_request($url, '', $cookie);
        $res = json_decode($res, true);
        $info = $res['data']['shiftSchedule'];
        $msg = '';
        $mark = 0;
        $subject = iconv("GBK", "UTF-8//IGNORE", "车医生没有班次");
        $content = iconv("GBK", "UTF-8//IGNORE", "程度运行正常");
        $to = array('zhangyang@2345.com');
        $date = array("2019-03-20", "2019-03-13");
        if (isset($info)) {
            foreach ($info as $index => $item) {
                $msg .= $item['date'] . $item['extraStateDesc'] . "\n";
                if (in_array($item['date'], $date) && $item['extraState'] == 1) {
                    $mark++;
                }
            }
            if ($mark > 0) {
                $subject = iconv("GBK", "UTF-8//IGNORE", "可以约车医生了");
                $content = $msg;
                array_push($to, "822326559@qq.com");
//                array_push($to, "332926195@qq.com");
            }
        }
        $flag = Mail::Raw($content, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
        var_dump($subject);
    }

    /**
     * 为指定用户显示详情
     * @return void
     */
    public function test()
    {
        error_log(var_export($_REQUEST, true) . "\n" . "----" . __FUNCTION__ . "-" . __LINE__ . "----" . "\n", 3, $_SERVER['DOCUMENT_ROOT'] . '/my-errors.log');
        $echoStr = $_REQUEST['echostr'];
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }


    /**
     * 校验数据
     * @return mixed
     */
    private function checkSignature()
    {
        $data = $_REQUEST;
        $signature = $data['signature'];
        $timestamp = $data['timestamp'];
        $nonce = $data['nonce'];
        $token = "Thisisacannotdecryptthetoken";
        $list = array($token, $timestamp, $nonce);
        sort($list, SORT_STRING);
        $tmpStr = implode($list);
        error_log(var_export($tmpStr, true) . "\n" . "----" . __FUNCTION__ . "-" . __LINE__ . "----" . "\n", 3, $_SERVER['DOCUMENT_ROOT'] . '/my-errors.log');
        $tmpStr = sha1($tmpStr);
        error_log(var_export($tmpStr, true) . "\n" . "----" . __FUNCTION__ . "-" . __LINE__ . "----" . "\n", 3, $_SERVER['DOCUMENT_ROOT'] . '/my-errors.log');
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
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
