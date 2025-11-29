<?php
// app/Console/Commands/DeleteUser.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class DeleteUser extends Command
{
    protected $signature = 'user:delete {email}';
    protected $description = 'Delete user by email';

    public function handle()
    {
        $email = $this->argument('email');
        User::where('email', $email)->delete();
        $this->info("User $email deleted");
    }
}
