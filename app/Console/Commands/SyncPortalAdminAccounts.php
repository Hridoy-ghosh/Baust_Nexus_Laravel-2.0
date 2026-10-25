<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class SyncPortalAdminAccounts extends Command
{
    protected $signature = 'nexus:sync-admin-accounts';

    protected $description = 'Create or update the Main, Cafe and Library administrator login accounts from .env.';

    public function handle(): int
    {
        $accounts = [
            'admin' => [
                'name' => 'BAUST System Admin',
                'department' => 'Administration',
                'email' => env('BAUST_MAIN_ADMIN_EMAIL', 'admin@baust.edu.bd'),
                'password' => env('BAUST_MAIN_ADMIN_PASSWORD'),
            ],
            'cafe_admin' => [
                'name' => 'Cafeteria Manager',
                'department' => 'Cafeteria',
                'email' => env('BAUST_CAFE_ADMIN_EMAIL', 'cafeadmin@baust.edu.bd'),
                'password' => env('BAUST_CAFE_ADMIN_PASSWORD'),
            ],
            'library_admin' => [
                'name' => 'Head Librarian',
                'department' => 'Library',
                'email' => env('BAUST_LIBRARY_ADMIN_EMAIL', 'libraryadmin@baust.edu.bd'),
                'password' => env('BAUST_LIBRARY_ADMIN_PASSWORD'),
            ],
        ];

        $emails = array_column($accounts, 'email');
        $passwords = array_column($accounts, 'password');

        if (count(array_unique($emails)) !== count($emails)) {
            $this->error('Main, Cafe and Library administrator email addresses must be unique.');

            return self::FAILURE;
        }

        if (count(array_filter($passwords, fn ($password) => is_string($password) && strlen($password) >= 12)) !== count($accounts)) {
            $this->error('Set a password of at least 12 characters for each administrator in .env.');

            return self::FAILURE;
        }

        if (count(array_unique($passwords)) !== count($passwords)) {
            $this->error('Main, Cafe and Library administrator passwords must all be different.');

            return self::FAILURE;
        }

        try {
            DB::transaction(function () use ($accounts): void {
                foreach ($accounts as $role => $details) {
                    $account = User::where('role', $role)->orderBy('id')->first();
                    $conflictingUser = User::where('email', $details['email'])
                        ->when($account, fn ($query) => $query->where('id', '!=', $account->id))
                        ->first();

                    if ($conflictingUser) {
                        throw new InvalidArgumentException("Email {$details['email']} is already assigned to another account.");
                    }

                    $account ??= new User([
                        'name' => $details['name'],
                        'department' => $details['department'],
                    ]);

                    $account->fill([
                        'email' => $details['email'],
                        'password' => Hash::make($details['password']),
                        'role' => $role,
                        'is_approved' => true,
                    ]);
                    $account->save();
                }
            });
        } catch (InvalidArgumentException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Main, Cafe and Library administrator accounts are ready with separate credentials.');

        return self::SUCCESS;
    }
}
