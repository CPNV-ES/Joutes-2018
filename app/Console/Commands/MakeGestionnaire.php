<?php

namespace App\Console\Commands;

use App\User;
use App\Role;
use Illuminate\Console\Command;

class MakeGestionnaire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:gestionnaire {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the user right to gestionnaire';

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
            $r = Role::where('slug', 'GEST')->first();
            $u->role()->associate($r);
            $u->save();
        
            $this->line("L'utilisateur $username est maintenant gestionnaire.");
            
        }else{     
            $this->line("Erreur: L'utilisateur $username n'existe pas.");
        }

    }
}
