<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class CreateAdmin extends Command
{
    protected $signature = 'app:create-admin';

    protected $description = 'Create an active administrator account';

    public function handle(): int
    {
        $name = trim((string) $this->ask('Administrator name'));
        $email = Str::lower(trim((string) $this->ask('Administrator email')));
        $password = $this->secret('Administrator password');
        $confirmation = $this->secret('Confirm administrator password');

        if ($name === '' || mb_strlen($name) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Enter a valid name and email address.');

            return self::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->error('An account with this email already exists. No account was changed.');

            return self::FAILURE;
        }

        if (!is_string($password) || !hash_equals($password, (string) $confirmation)) {
            $this->error('The password confirmation did not match.');

            return self::FAILURE;
        }

        $validator = Validator::make(
            ['password' => $password],
            ['password' => [Password::min(12)->mixedCase()->numbers()->symbols()]]
        );

        if ($validator->fails()) {
            $this->error($validator->errors()->first('password'));

            return self::FAILURE;
        }

        $admin = new User([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        $admin->role = 'admin';
        $admin->status = 'active';
        $admin->save();

        $this->info("Administrator account created for {$email}.");

        return self::SUCCESS;
    }
}
