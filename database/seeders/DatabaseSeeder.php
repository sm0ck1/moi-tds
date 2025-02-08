<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\Partner;
use App\Models\PartnerLink;
use App\Models\Portal;
use App\Models\PortalPartnerLink;
use App\Models\Topic;
use App\Models\User;
use App\Models\VisitUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    private $topics = [
        'Dating',
        'Download',
        'E-commerce',
        'Education',
        'Gambling ',
        'Games',
    ];

    public function run()
    {
        User::factory()->create([
            'name'     => 'Admin',
            'email'    => env('ADMIN_LOGIN') ?? 'admin@admin.com',
            'password' => Hash::make(env('ADMIN_PASSWORD') ?? 'password'),
        ]);


        foreach ($this->topics as $name) {
            Topic::factory()->create([
                'name' => $name
            ]);
        }
//        Domain::factory()->create([
//            'name' => 'lookonlooks.com',
//            'date_registration' => '2021-01-01',
//            'dns_provider_login' => 'admin',
//            'dns_provider' => 'GoDaddy',
//        ]);
//
//        Partner::factory()->count(rand(5, 15))->create();
//        PartnerLink::factory()->count(rand(5, 15))->create();
//        Portal::factory()->count(rand(5, 15))->create();
//        Portal::all()->each(function ($portal) {
//            PortalPartnerLink::factory()
//                ->forPortal($portal->id, fake()->numberBetween(2, 5))
//                ->create();
//        });
//
//        VisitUser::factory()->count(rand(120, 265))->create();
        //$this->call(VisitUserSeeder::class);


    }
}
