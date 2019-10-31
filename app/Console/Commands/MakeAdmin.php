<?php

namespace App\Console\Commands;

use App\User;
use App\Role;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the user right to administrator';

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

        if(User::where('username', '=', $username)->exists()){
            $u = User::where('username', $username)->first();
            $r = Role::where('slug', 'ADMIN')->first();
            $u->roles()->associate($r);
            $u->save();
        
            $this->line("L'utilisateur $username est maintenant administrateur.");
        
        }else{     
            $this->line("Erreur: L'utilisateur $username n'existe pas.");
        }

    }
}
