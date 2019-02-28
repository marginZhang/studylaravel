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

    public function __construct()
    {
        parent::__construct();
        // 初始化代码写到这里，也没什么用
    }

    public function handle()
    {
        echo "start\n";
        $url = "https://www.guahao.com/expert/new/shiftcase/?expertId=74a96953-2aaf-4be0-be33-63c25d9bf6aa000&hospDeptId=43d801b5-8bf7-4b9d-93c7-4f0cc354c824000&hospId=5f97d681-0528-40ac-8e43-a4db5cac6c7f000&_=1551240184822";
        $cookie = '_sid_=1551230786858018725013182; _e_m=1551230786868; monitor_sid=1; mst=1551230787758; _fp_code_=ced6fc3fb789b0407463bdd1ecbbe3b5; _fm_code=e3Y6ICIyLjUuMCIsIG9zOiAid2ViIiwgczogMTk5LCBlOiAianMgbm90IGRvd25sb2FkIn0%3D; _ci_=1+a0EpiqVtj9cfQhgFLelZvl2OfWJtsve6Ib8VHelMtaST7odHGIyUV9hi37s/ey; __i__=ueg1oMqZVjym7WMexn0WIvtASDq/UdojIUH9drQOZuA=; __uiu__=Z35Cxt4X3PY3YK+AUbpSIAq0CHuXoj4lk155hFMJ/I3P8R/9xbko0A==; __usx__=3B5Fx7NR9J9b1shCz836qOjmD2ZBLa6Dsavvkeq2GRU=; __up__=1a5P7icd01ZNKIyG8MnBPs+EFQF6yIyDmAYVu8H0PgA=; _exp_=gukd3T5fyRE4d9QMlqlDWSH3fVbk42NZmnCfSmmqQz0=; __p__=0YRn19Pp3mHXFoNgfc3hGgPYxb8gz3lZ20Qug9GrkCPKcOuT1gxMmA==; __uli__=p+JiDO+FAukGkrbvjUQZt0qQlPVbGo4iIMVwMH1j0nm7HeWmYi863v29y1pbzXCJlV/vFcW55ps=; __un__=DhF5YoihWeDz9ppuyqlk6Yce3trenLfbDQvTfHxIv1xOUXBedqIz+u/IufJIT3Pw; __wyt__=!PqKRPWocxAd-TDdNusfTZ7H8jFpo91oQ9DJn3G7iRK5WRyqRopEmFbF9aqaeAl6T088bUTe8acYIl5g5yygmGIZj4aNqz49ZLFM5CGi7LhA0BXUMJG-6ChkCHOE2AKqRBS2XUPmuj1lBAnpLoBQQR0gwZgzITm9jX5qr-K4UJscow; __rf__=MM6HkL5iUAp4uI0zmtFE3rSvEO3SiD8wRunX1BFhXvZCNtGitM7mAccbv5X1pjmyLVBMz7zEBz0eZDJyECstKQ==; monitor_seq=5; mlt=1551230821929';
        $res = $this->curl_request($url, '', $cookie);
        $res = json_decode($res, true);
        $info = $res['data']['shiftSchedule'];
        $msg = '';
        $mark = 0;
        $subject = "车医生没有班次";
        $content = "脚本运行正常";
        $to = array('zhangyang@2345.com');
        $date = array("2019-03-20", "2019-03-13");
        if (isset($info)) {
            foreach ($info as $index => $item) {
                $msg .= $item['date'] . iconv("UTF-8", "GBK", $item['extraStateDesc']) . "\n";
                if (in_array($item['date'], $date) && $item['extraState'] == 1) {
                    $mark++;
                }
            }
            if ($mark > 0) {
                $subject = "可以约车医生了";
                $content = $msg;
                array_push($to, "822326559@qq.com");
//                array_push($to, "332926195@qq.com");
            }
        }
        $flag = Mail::Raw($content, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
        var_dump($flag);
        echo "end\n";
    }

    /**
     * 发邮件功能
     * @param string $content 接收数据
     * @param string $subject 发送数据
     * @author：zuocongbing
     * @date  ：2017-10-17 15:59
     * @return int
     */
    private function sendMail($content = '', $subject = '')
    {
        $to = 'zhangyang@2345.com';
        $flag = Mail::Raw($content, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
        return $flag;
    }
}
