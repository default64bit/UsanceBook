<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OauthClients extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
            [
                'id' => '2',
                'name' => 'Refresh Token',
                'secret' => 'hqiIfZZQPrKa2ANRHtQoYtxhfxf0thycrcBAVB5h',
                'provider' => '',
                'redirect' => 'http://localhost/auth/callback',
                'personal_access_client' => '0',
                'password_client' => '0',
                'revoked' => '0',
                'created_at' => '2020-08-07 15:12:53',
                'updated_at' => '2020-08-07 15:12:53',
            ],
            [
                'id' => '3',
                'name' => 'Password Grant Client',
                'secret' => '3gT2zDT5iVBm338IGoB6YHYTOpQreTnr3y3tEDGU',
                'provider' => 'users',
                'redirect' => 'http://localhost',
                'personal_access_client' => '0',
                'password_client' => '1',
                'revoked' => '0',
                'created_at' => '2020-08-07 16:14:11',
                'updated_at' => '2020-08-07 16:14:11',
            ],
        ]);
    }
}
