<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Role;
use App\Models\User;
use App\Models\MasterBank;

use Illuminate\Support\Facades\File;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonCities = File::get(database_path('json/cities.json'));
        $dataCities = json_decode($jsonCities);
        $dataCities = collect($dataCities);

        foreach ($dataCities as $d) {
            $d = collect($d)->toArray();
            $p = new City();
            $p->fill($d);
            $p->save();
        }

        User::insert([
            'name' => 'Pablo Escobar',
            'password' => bcrypt('password123'), //password123 
            'role_id' => 4,
            'verified' => 1,
            'otp' => 1111,
            'phone' => '+628123456789',
            'password_show' => 'password123'
        ]);

        User::insert([
            'name' => 'Admin Pablo Escobar',
            'password' => bcrypt('password123'), //password123
            'role_id' => 2,
            'verified' => 1,
            'otp' => 1111,
            'email' => 'admin-escobar@mail.com',
            'password_show' => 'password123'
        ]);

        User::insert([
            'name' => 'Jury Pablo Escobar',
            'password' => bcrypt('password123'), //password123
            'role_id' => 3,
            'verified' => 1,
            'otp' => 1111,
            'email' => 'jury-escobar@mail.com',
            'password_show' => 'password123'
        ]);

        Role::insert([
            'name' => 'Admin',
            'role_type' => 'admin',
            'max_buy' => 0
        ]);

        Role::insert([
            'name' => 'Owner',
            'role_type' => 'owner',
            'max_buy' => 0
        ]);

        Role::insert([
            'name' => 'Juri',
            'role_type' => 'jury',
            'max_buy' => 0
        ]);

        Role::insert([
            'name' => 'Basic Member',
            'role_type' => 'user',
            'max_buy' => 1
        ]);

        Role::insert([
            'name' => 'Member 1',
            'role_type' => 'user',
            'max_buy' => 2
        ]);

        MasterBank::insert([
            'name' => 'BCA',
            'url' => '/storage/bank-img/bca.svg'
        ]);
        MasterBank::insert([
            'name' => 'BNI',
            'url' => '/storage/bank-img/bni.svg'
        ]);
        MasterBank::insert([
            'name' => 'BTPN',
            'url' => '/storage/bank-img/btpn.svg'
        ]);
        MasterBank::insert([
            'name' => 'CIMB NIAGA',
            'url' => '/storage/bank-img/cimb.svg'
        ]);
        MasterBank::insert([
            'name' => 'CITI',
            'url' => '/storage/bank-img/citi.svg'
        ]);
        MasterBank::insert([
            'name' => 'GOPAY',
            'url' => '/storage/bank-img/gopay.svg'
        ]);
        MasterBank::insert([
            'name' => 'JATIM',
            'url' => '/storage/bank-img/jatim.svg'
        ]);
        MasterBank::insert([
            'name' => 'MANDIRI',
            'url' => '/storage/bank-img/mandiri.svg'
        ]);
        MasterBank::insert([
            'name' => 'MASTERCARD',
            'url' => '/storage/bank-img/mastercard.svg'
        ]);
        MasterBank::insert([
            'name' => 'MAYBANK',
            'url' => '/storage/bank-img/maybank.svg'
        ]);
        MasterBank::insert([
            'name' => 'MUAMALAT',
            'url' => '/storage/bank-img/muamalat.svg'
        ]);
        MasterBank::insert([
            'name' => 'PERMATA',
            'url' => '/storage/bank-img/permata.svg'
        ]);
        MasterBank::insert([
            'name' => 'VISA',
            'url' => '/storage/bank-img/visa.svg'
        ]);
    }
}
