<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-unverified';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Menghapus pengguna yang belum diverifikasi dalam waktu tertentu';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $expiredUsers = User::whereNull('email_verified_at')
            ->where('verification_expires_at', '<', Carbon::now())
            ->delete();

        $this->info("Total akun yang dihapus: {$expiredUsers}");

    }
}
