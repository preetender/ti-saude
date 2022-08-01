<?php

namespace Database\Seeders;

use App\Domain\Plans\Models\Plan;

use Illuminate\Database\Seeder;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            [
                'description' => 'Plano Básico',
                'phone' => '6730562157',
                'code' => '0001',
            ],
            [
                'description' => 'Plano Intermediario',
                'phone' => '6730562157',
                'code' => '0002',
            ],
            [
                'description' => 'Plano Corporativo',
                'phone' => '6730562157',
                'code' => '0003',
            ],
            [
                'description' => 'Plano Familia+',
                'phone' => '6730562157',
                'code' => '0004',
            ]
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['code' => $plan['code']], $plan);
        }
    }
}
