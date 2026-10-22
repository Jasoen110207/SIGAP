<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class EnsureAdminUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ensure-admin {--name=Admin Kelurahan} {--email=admin@sigap.id} {--password=password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure at least one admin user exists in the database.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->option('email');

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update([
                'role' => User::ROLE_ADMIN ?? 'admin',
                'is_active' => true,
            ]);
            $this->info("User admin dengan email {$email} berhasil diperbarui menjadi admin.");
        } else {
            User::create([
                'name' => $this->option('name'),
                'email' => $email,
                'password' => Hash::make($this->option('password')),
                'role' => User::ROLE_ADMIN ?? 'admin',
                'is_active' => true,
            ]);
            $this->info("User admin baru ({$email}) berhasil dibuat.");
        }

        return Command::SUCCESS;
    }
}