<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class DeleteUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete {user : The ID of the user to delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an existing user';

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

        $user->delete();
        $this->line("Deleted user {$user->id}");
    }
}
