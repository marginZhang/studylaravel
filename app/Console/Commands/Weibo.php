<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Weibo extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'weibo:get';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Command description';

    protected $request;

    protected $dataDir = '/opt/case/studylaravel/mblog.log';

    protected $handle;

    protected $cookies = 'ALF=1574918252; SCF=AtzR-kFibecdPAN1GiEw9uOnb8iVgbMQtuulZj7Qh6p5WgrJqa4H-71QnjQCeabUipjRYRKwnrA_FBr5MMfGT-Q.; SUB=_2A25ws5gRDeRhGeRP61oT8SbLzDiIHXVQXzhZrDV6PUJbktANLWSskW1NUFtyIwXoomM3WeopMCXEyvBiK1oVO_gv; SUBP=0033WrSXqPxfM725Ws9jqgMF55529P9D9WhXM0-x1s1cQRs_DY2429Qp5JpX5K-hUgL.FozpehnEeKnNS0B2dJLoI7L9dNHV9NxaqJ-t; SUHB=0mxlvnFI2dGNZ4; _T_WM=73575964948; MLOGIN=1; WEIBOCN_FROM=1110106030; M_WEIBOCN_PARAMS=luicode%3D10000011%26lfid%3D1005052108218774%26fid%3D1076032108218774%26uicode%3D10000011';

    public $st;

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->request = new SendEmails();


    }

    public function __destruct()
    {
        fclose($this->handle);
    }

    /**
     * Create a new command instance.
     * @return string
     */
    public function getRandMsg()
    {
        $replyMsg = array(
            '抢个沙发不会被血吼爆头吧！',
            '抢个沙发不会被炎爆吧！',
            '抢个沙发不会被发牌员制裁吧！',
            '抢个沙发不会被律师函警告吧！',
        );

        return $replyMsg[array_rand($replyMsg)];
    }

    /**
     * Create a new command instance.
     * @return string
     */
    public function rData()
    {
        $this->handle = fopen($this->dataDir, "r");

        return fread($this->handle, filesize($this->dataDir));
    }


    /**
     * Create a new command instance.
     * @return void
     */
    public function wData($text)
    {
        $this->handle = fopen($this->dataDir, "w");
        fwrite($this->handle, $text);
        fclose($this->handle);
    }

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $id = $this->rData();
        $url  = 'https://m.weibo.cn/api/container/getIndex?containerid=1076031660963912';
//        $url = 'https://m.weibo.cn/api/container/getIndex?containerid=1076032108218774';
        $newID = $this->getFirst($url);
        if ($newID !== $id) {
            $this->reply($newID);
        }
    }

    private function getFirst($url)
    {
        $res    = $this->request->curl_request($url);
        $res    = json_decode($res, true);
        $cards  = $res['data']['cards'];
        $id = 0;
        foreach ($cards as $index => $card) {
            if ($card['card_type'] !== 9 || isset($card['mblog']['isTop'])) {
                continue;
            }
            echo $card['mblog']['text'] . "\n";
            $id = $card['mblog']['id'];
            $this->wData($id);
            break;
        }

        return $id;
    }

    private function getCookies()
    {
        $url = 'https://m.weibo.cn/p/1005052108218774';

        $header = $this->request->curl_request($url, '', $this->cookies, 1);

        preg_match_all("/XSRF\-TOKEN\=([^;]*);/", $header['cookie'], $matches);
        $this->st = $matches[1][1];
        $this->cookies .= "; XSRF-TOKEN=" . $this->st;
    }

    private function reply($id)
    {
        $this->getCookies();
        $url  = 'https://m.weibo.cn/api/comments/create';
        $post = array(
            'id'      => $id,
            'content' => $this->getRandMsg(),
            'mid'     => $id,
            'st'      => $this->st,
        );
        $res  = $this->request->curl_request($url, $post, $this->cookies);
        $res  = json_decode($res, true);
        var_export($res['ok']);
    }
}
