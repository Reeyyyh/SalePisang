<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class DeleteProducts extends Command
{
    protected $signature = 'product:delete-all';
    protected $description = 'Force delete all products (for testing cleanup)';

    public function handle()
    {
        $count = Product::withTrashed()->count();

        if ($count === 0) {
            $this->warn("Tidak ada produk untuk dihapus.");
            return Command::SUCCESS;
        }

        Product::withTrashed()->forceDelete();
        $this->info("Semua produk berhasil dihapus.");
        return Command::SUCCESS;
    }
}
