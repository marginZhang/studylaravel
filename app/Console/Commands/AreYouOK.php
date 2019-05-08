<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AreYouOK extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'areyou:ok';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $url = "https://www.guahao.com/expert/new/shiftcase/?expertId=74a96953-2aaf-4be0-be33-63c25d9bf6aa000&hospDeptId=4fe6f974-e0b5-4405-9b3e-c3c01b0d6936000&hospId=5f97d681-0528-40ac-8e43-a4db5cac6c7f000&_=1557040896873";
        $cookie = '_sid_=1551230786858018725013182; _fp_code_=ced6fc3fb789b0407463bdd1ecbbe3b5; searchHistory=%E8%BD%A6%E9%9C%9E%E9%9D%99%2C%7C%2Cclear; _ipgeo=province%3A%E4%B8%8A%E6%B5%B7%7Ccity%3A%E4%B8%8D%E9%99%90; _sh_ssid_=1557040827668; _e_m=1557040827673; monitor_sid=6; mst=1557040828090; Hm_lvt_5697507823ecd633819db0771bb99cfb=1557040828; _ci_=Q1tYIY73Qw947ldFqarK6BNP9Y9K0FWwAbps3QlT0NheHdvdXCez/P0EBF++bA+t; __i__=r9BaN7jDdh2B0vDtKnKwdsEW6kWtrFcxhxa+3l9EKTk=; __uiu__=aP7TYC7ZDEs+jsu5dSirbSErHysUD7UEjv+7mjE8EhlWrGeErQqu/w==; __usx__=vM+Vx7DSvvk90RPIP0U4JW9rqfu6b15MLQTe6zKFQe4=; __up__=BWDCq+bT7LPEr8b0x4CJ0hw8nXp4hVsmmbhY7nv7Wg0=; _exp_=KhEdiPK6bbacNNJk8sTtwD51RjTBIo56QrW7nrf6CHs=; __p__=3DiMua/EMe5tS5yTD/aCo8uqSW/1GZnm7qiVaLn31mhavnUE52bfVA==; __uli__=DKX1CCN4++0gmKGDUtzvFHYpyHglAKnfTNrYwAqMDGUxT8gfoeegEAWGN8yq7Gt2bI6fxdx0Eo8=; __un__=5XlmljgcPq2ilKB8k2ETOtRRwCyw5XGVp1eaJM6n4BzxVI89zGoqoGpoqKcNOEoM; __wyt__=!P-kZT12PUXk1dls9f2hcn2k8jFpo9qoQ9DJn3G7iRK5WQqJnOg9PvplK0kDtoXo1Dphgw1qA_GDiQcQIZa6WPjadd1qFDCl03bmC12QwZSAnpXUMJG-6ChkCHOE2AKqRBS2XUPmuj1lBAnpLoBQQR0g_Lme5HzM38tKcFgg8esr9A; __rf__=xCbd5TXPYK4o/IxsKByLHG6LuZIcf2q875ThBSouKEFDIW3Sq3f2MKaCAsROo3AwD85eA9oCBL0JCwsOAIvN5I8LKUkN6ocKCpVwMt7xOmo=; Hm_lpvt_5697507823ecd633819db0771bb99cfb=1557040891; _fm_code=e3Y6ICIyLjUuMCIsIG9zOiAid2ViIiwgczogMTk5LCBlOiAianMgbm90IGRvd25sb2FkIn0%3D; monitor_seq=6; mlt=1557040891813; _fmdata=IVW6AU71qSzTtLlnS0UU4TKsZ4W3BagZ9FmwH0l9OVZQTXPVfqUjB6uaSNt9i%2Fo4EwVnIwMuSyZIVIJMkq6gTP0DVga%2BF3Gz%2FCufTygdoCI%3D';
        $res = $this->curl_request($url, '', $cookie);
        $res = json_decode($res, true);
        $info = $res['data']['shiftSchedule'];
        $msg = '';
        $mark = 0;
        $subject = iconv("GBK", "UTF-8//IGNORE", "车医生没有班次AreYouOK");
        $to = array('822326559@qq.com');
        $date = array("2019-05-22");
        if ($info[0]['extraState'] != 'null') {
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
        } else {
            $subject = iconv("GBK", "UTF-8//IGNORE", "cookie过期了");
        }
        Mail::Raw($content, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
        echo $info[0]['extraState'] . "\n";
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
