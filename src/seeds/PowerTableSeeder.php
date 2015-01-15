<?php

class PowerTableSeeder extends Seeder
{

    public function __construct()
    {
        $this->prefix = Config::get('power::prefix', '');
    }

    public function run()
    {
        $tablePrefix = $this->prefix;

        DB::table($tablePrefix.'groups')->insert([
            'name' => 'super_admin',
            'description' => 'Group For Super administrators',
            'level' => 5,
        ]);

        Group::create([
            'name' => 'admin',
            'description' => 'Group For administrators',
            'level' => 3,
        ]);

        Group::create([
            'name' => 'updater',
            'description' => 'Group For content updater',
            'level' => 2,
        ]);

        /**
         * Create a Test user
         */
        $user_id = DB::table($tablePrefix.'users')->insertGetId([
            'username' => 'admin',
            'password' => '$2y$10$wMrGleZeXtVCRs7Q/A/bIuXlT2VKvommLeIucYSLGLMm/2sauciKS',
            'salt'      => 'bf00a6835ad879c37cec1f81978f11f7',
            'email' => 'admin@example.com',
            'verified' => 1,
            'disabled' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table($tablePrefix.'groupUser')->insert([
            'groupId' => 1,
            'userId' => $user_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        /**
         * Create Groups privileges
         */
        Privilege::create([
            'name' => 'super_admin',
            'description' => 'Privilege that does all things',
        ]);

        Privilege::create([
            'name' => 'admin',
            'description' => 'Privilege that does many things',
        ]);

        Privilege::create([
            'name' => 'updater',
            'description' => 'Privilege For content updater',
        ]);
    }

}