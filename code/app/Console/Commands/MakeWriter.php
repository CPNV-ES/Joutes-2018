<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeWriter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:writer {username} {password} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new writer';

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
        $username = $this->argument('username');
        $password = Hash::make($this->argument('password'));
        $email = $this->argument('email');

        if(User::where('username', '=', $username)->exists()){
            $this->line("Error: The username \"".$username."\" already exists.");
        }else{
            $user = new User;
            $user->username = $username;
            $user->password = $password;
            $user->email = $email;
            $user->first_name = $username;
            $user->last_name = $username;
            $user->role = 'writer';
            $user->save();
            $this->line("The writer \"".$username."\" has been created.");
        }

    }
}
