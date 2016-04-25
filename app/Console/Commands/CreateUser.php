<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Faker;
use App\User;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user record with random values';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $faker = Faker\Factory::create();

        $user = new User;
        $user->name = $faker->name();
        $user->email = $faker->safeEmail();
        $user->password = $faker->password();
        $user->save();

        $this->line("Created new user:");
        $this->table(
            ['id','name','email'],
            [[$user->id, $user->name, $user->email]]
        );
    }
}
