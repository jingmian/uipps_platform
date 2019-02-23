<?php

use Illuminate\Database\Seeder;

class project extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 项目表初始项目 - 项目本身
        DB::table('project')->insert([
            'id' => 1,
            'name_cn' => '通用发布系统',
            'type' => 'SYSTEM',
            'parent_id' => 0,
            'table_field_xiangmu_id' => 0,
            'creator' => 0,
            'mender' => '',
            'db_host' => '127.0.0.1',
            'db_name' => 'uipps_platform',
            'db_port' => 3307,
            'db_user' => 'root',
            'db_pwd' => '10y9c2U5',
            'db_timeout' => 0,
            'db_sock' => '',
            'if_use_slave' => 'F',
            'slave_db_host' => '',
            'slave_db_name' => '',
            'slave_db_port' => 0,
            'slave_db_user' => '',
            'slave_db_pwd' => '',
            'slave_db_timeout' => 0,
            'slave_db_sock' => '',
            'if_daemon_pub' => 'F',
            'status_' => 'use',
            'search_order' => 0,
            'list_order' => 50,
            'if_hide' => 'F',
            'description' => '',
            'host_id' => 0,
            'res_pub_map' => 0,
            'website_name_cn' => '就你网',
            'waiwang_url' => 'https://www.uipps.com',
            'bendi_uri' => '/data0/htdocs/www',
            'created_at' => time(),
        ]);

        // faker相对逼真的数据, php artisan make:factory UserFactory --model=User 创建的，在database/factory下
        //factory(App\Models\User\User::class)->times(10)->make()->each(function($user, $index){
        //    $user->save();
        //});

        // 开天辟地初始用户
        DB::table('user')->insert([
            [
                'id' => 1,
                'g_id' => 0,
                'parent_id' => 0,
                'username' => 'robot',
                'password' => '21232f297a57a5a743894a0e4a801fc3',
                'nickname' => '后台机器人',
                'mobile' => '18601357705',
                'telephone' => '18601357705',
                'email' => 'admin@uipps.com',
                'locked' => 'F',
                'is_admin' => 'T',
                'creator' => '',
                'mender' => '',
                'status_' => 'use',
                'expired' => '3000-12-12 00:00:00',
                'description' => 'Robot,alias,nickname',
                'created_at' => time(),
            ],
            [
                'id' => 2,
                'g_id' => 0,
                'parent_id' => 0,
                'username' => 'admin',
                'password' => '21232f297a57a5a743894a0e4a801fc3',
                'nickname' => '超级管理员',
                'mobile' => '18601357705',
                'telephone' => '18601357705',
                'email' => 'admin@uipps.com',
                'locked' => 'F',
                'is_admin' => 'T',
                'creator' => '',
                'mender' => '',
                'status_' => 'use',
                'expired' => '3000-12-12 00:00:00',
                'description' => 'Administrator',
                'created_at' => time(),
            ],
            [
                'id' => 3,
                'g_id' => 0,
                'parent_id' => 0,
                'username' => 'grab',
                'password' => '21232f297a57a5a743894a0e4a801fc3',
                'nickname' => '抓取机器人',
                'mobile' => '18601357705',
                'telephone' => '18601357705',
                'email' => 'admin@uipps.com',
                'locked' => 'F',
                'is_admin' => 'T',
                'creator' => '',
                'mender' => '',
                'status_' => 'use',
                'expired' => '3000-12-12 00:00:00',
                'description' => 'Grab',
                'created_at' => time(),
            ]
        ]);
    }
}
