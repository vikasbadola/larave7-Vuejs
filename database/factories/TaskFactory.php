<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use App\User;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'userID' => User::all()->random()->id,
        'name' => $faker->name,
        'desc' => $faker->sentence,
        'status' => 'P'
    ];
});
