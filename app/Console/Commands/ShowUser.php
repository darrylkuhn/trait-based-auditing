<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class ShowUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:show {user : The ID of the user, or the string "rand"}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Print a table with user details';

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

        $this->table(
            ['id','name','email'],
            [[$user->id, $user->name, $user->email]]
        );
    }
}
