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
        echo 123;
        exit;
        // 功能代码写到这里
        $user = new User();
        $user->name = '大哥';
        $user->age = 33;
        $user->save();

        echo "<pre>";
        print_r('haole');die;
    }
}
