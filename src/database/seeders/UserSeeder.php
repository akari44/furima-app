<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use App\Models\User;

class UserSeeder extends Seeder
{
   
    public function run()
    {
        $avatarPaths = collect(range(1, 5))
            ->map(fn($n) => 'avatars/avatar_' . str_pad($n, 2, '0', STR_PAD_LEFT) . '.jpg')
            ->all();

        User::updateOrCreate(
            ['email' => 'akari@gmail.com'],
            [
                'name' => 'akari',
                'password' => Hash::make('akariakari'),
                'postal_code' => '060-0000',
                'address' => '北海道紋別市流氷町111',
                'building_name' => 'みはらしビルディング303',
                'avatar_path' => 'avatars/avatar_01.jpg',
            ]
        );

        User::updateOrCreate(
            ['email' => 'coachtech@gmail.com'],
            [
                'name' => 'coachtech',
                'password' => Hash::make('coachtech'),
                'postal_code' => '123-4567',
                'address' => '東京都',
                'building_name' => 'エストラ北海道ビル',
                'avatar_path' => 'avatars/avatar_02.jpg',
            ]
        );

        User::updateOrCreate(
            ['email' => 'aki@gmail.com'],
            [
                'name' => 'aki',
                'password' => Hash::make('akiakiaki'),
                'postal_code' => '222-2222',
                'address' => '沖縄県那覇市パイナップル地区',
                'building_name' => '',
                'avatar_path' => 'avatars/avatar_03.jpg',
            ]
        );

        // ランダムユーザー7人
        User::factory()->count(7)->create();
    }
}
