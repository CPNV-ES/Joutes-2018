<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {username} {password} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new administrator';

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
            $this->line("Erreur: L'utilisateur $username existe déjà.");
        }else{
            $user = new User;
            $user->username = $username;
            $user->password = $password;
            $user->email = $email;
            $user->first_name = $username;
            $user->last_name = $username;
            $user->role = 'administrator';
            $user->save();
            $this->line("L'administrateur $username a bien été créé.");
        }

    }
}
