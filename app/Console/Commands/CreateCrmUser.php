<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateCrmUser extends Command
{
    protected $signature = 'crm:user {email} {--name= : Display name} {--reset : Set a new password for an existing user}';

    protected $description = 'Create a CRM user (public registration is disabled) or reset their password';

    public function handle(): int
    {
        $email = strtolower($this->argument('email'));
        $user = User::where('email', $email)->first();

        if ($user && ! $this->option('reset')) {
            $this->error("User {$email} already exists. Use --reset to set a new password.");

            return self::FAILURE;
        }

        $name = $this->option('name') ?: $user?->name ?: $this->ask('Name');
        $password = $this->secret('Password');

        $validator = Validator::make(
            ['email' => $email, 'name' => $name, 'password' => $password, 'password_confirmation' => $this->secret('Repeat password')],
            ['email' => ['required', 'email'], 'name' => ['required', 'string', 'max:255'], 'password' => ['required', Password::default(), 'confirmed']],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        $user ??= new User(['email' => $email]);
        $user->forceFill(['name' => $name, 'password' => Hash::make($password)])->save();

        $this->info("Saved {$email}. Two-factor setup is required at the first login.");

        return self::SUCCESS;
    }
}
