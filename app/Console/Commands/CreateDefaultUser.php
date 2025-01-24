<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateDefaultUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bugtrackly:default_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a default user by requesting interactive information';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Creating default admin user...');
        $firstName = $this->ask('Enter the user\'s first name');
        $lastName = $this->ask('Enter the user name');
        $email = $this->ask('Enter the user\'s email address');
        $password = $this->secret('Enter a password (will be hidden)');

        // Valider que l'email est unique
        if (User::where('email', $email)->exists()) {
            $this->fail('A user with this email address already exists.');
        }

        // Créer l'utilisateur
        $user = User::create([
            'first_name'        => $firstName,
            'last_name'         => $lastName,
            'email'             => $email,
            'role_id'           => 1,
            'password'          => Hash::make($password),
            'email_verified_at' => Carbon::now()
        ]);

        $this->info('The user ' . $user->full_name . ' has been created. He is an administrator.');
    }
}
