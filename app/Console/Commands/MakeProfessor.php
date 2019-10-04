<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeProfessor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:professor {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the user right to professor';

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
            User::where('username', $username)->update(array('roles_id' => '2'));
        
            $this->line("L'utilisateur $username est maintenant professor.");
            
        }else{     
            $this->line("Erreur: L'utilisateur $username n'existe pas.");
        }

    }
}
