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

class AreYouOK extends Command
{
    protected $signature = 'areyou:ok';
    protected $description = '�׾����Ƽ�Ȧ��ᳪ�������';

    public function __construct()
    {
        parent::__construct();
        // ��ʼ������д�����Ҳûʲô��
    }

    public function handle()
    {
        echo 123;
        exit;
        // ���ܴ���д������
        $user = new User();
        $user->name = '���';
        $user->age = 33;
        $user->save();

        echo "<pre>";
        print_r('haole');die;
    }
}
