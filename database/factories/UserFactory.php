<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'UserID' => '11936',
            'UserName' => '11936',
            'Password' => bcrypt('123123'),
            'Email' => 'shojibul1991@gmail.com',
            'MobileNo' => '01553717992',
            'JoinDate' => Carbon::now(),
            'Active' => 'Y',
            'EntryBy' => '11936',
            'EntryDate' => Carbon::now(),
            'EntryIpAddress' => \Request::ip(),
            'EntryDivice' => 'desktop',
            'EditedBy' => '11936',
            'EditedDate' => Carbon::now(),
            'EditedIpAddress' => \Request::ip(),
            'EditedDivice' => 'desktop',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
