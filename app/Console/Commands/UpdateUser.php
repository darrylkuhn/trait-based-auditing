<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Faker;
use App\User;

class UpdateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update {user : The ID of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the email address of an existing user';

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
        $userId = $this->argument('user');

        if ( is_numeric($userId) )
        {
            $user = User::findorfail( $userId );
        }
        else
        {
            $user = User::orderByRaw('RANDOM()')->first();
        }

        $faker = Faker\Factory::create();

        $user->name = $faker->name();
        $user->email = $faker->safeEmail();
        $user->save();

        $this->table(
            ['id','name','email'],
            [[$user->id, $user->name, $user->email]]
        );
    }
}
