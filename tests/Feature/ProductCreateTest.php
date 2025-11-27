<?php

namespace Tests\Feature;

use App\Filament\Resources\ProductResource\Pages\CreateProduct;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductCreateTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;
    protected User $seller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::factory()->create();

        $this->seller = User::factory()->create([
            'role' => 'seller'
        ]);
    }

    /** @test */
    public function test_seller_can_create_product()
    {
        $this->actingAs($this->seller);

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'product_name' => 'Produk Seller',
                'price'        => 20000,
                'stock'        => 5,
                'weight'       => 0.5,
                'description'  => 'Deskripsi seller',
                'category_id'  => $this->category->id,
                'status'       => 'available',
                'is_verified'  => true,
                'is_featured'  => false,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('products', [
            'product_name' => 'Produk Seller',
            'user_id'      => $this->seller->id,
        ]);
    }

    /** @test */
    public function test_cannot_create_without_category()
    {
        $this->actingAs($this->seller);

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'product_name' => 'Tanpa Kategori',
                'price'        => 10000,
                'stock'        => 1,
                'description'  => 'Desc',
                'status'       => 'available',
            ])
            ->call('create')
            ->assertHasFormErrors(['category_id' => 'required']);
    }

    /** @test */
    public function test_required_fields_validation()
    {
        $this->actingAs($this->seller);

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'product_name' => '',
                'price'        => null,
                'stock'        => null,
                'description'  => '',
                'category_id'  => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'product_name' => 'required',
                'price'        => 'required',
                'stock'        => 'required',
                'description'  => 'required',
                'category_id'  => 'required',
            ]);
    }

    /** @test */
    public function test_product_name_max_length()
    {
        $this->actingAs($this->seller);

        $long = str_repeat('a', 300);

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'product_name' => $long,
                'price'        => 15000,
                'stock'        => 1,
                'description'  => 'desc',
                'category_id'  => $this->category->id,
                'status'       => 'available',
            ])
            ->call('create')
            ->assertHasFormErrors(['product_name' => 'max']);
    }

    /** @test */
    public function test_price_and_stock_must_be_numeric()
    {
        $this->actingAs($this->seller);

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'product_name' => 'Invalid Numeric',
                'price'        => 'abc',
                'stock'        => 'xyz',
                'description'  => 'desc',
                'category_id'  => $this->category->id,
                'status'       => 'available',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'price' => 'numeric',
                'stock' => 'numeric',
            ]);
    }

    /** @test */
    public function test_price_and_stock_cannot_be_negative()
    {
        $this->actingAs($this->seller);

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'product_name' => 'Negatif',
                'price'        => -1000,
                'stock'        => -5,
                'description'  => 'desc',
                'category_id'  => $this->category->id,
                'status'       => 'available',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'price' => 'min',
                'stock' => 'min',
            ]);
    }

    /** @test */
    public function test_price_max_value()
    {
        $this->actingAs($this->seller);

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'product_name' => 'Harga Terlalu Besar',
                'price'        => 10000001,
                'stock'        => 1,
                'description'  => 'desc',
                'category_id'  => $this->category->id,
                'status'       => 'available',
            ])
            ->call('create')
            ->assertHasFormErrors(['price' => 'max']);
    }

    /** @test */
    public function test_stock_max_value()
    {
        $this->actingAs($this->seller);

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'product_name' => 'Stok Terlalu Besar',
                'price'        => 10000,
                'stock'        => 10000,
                'description'  => 'desc',
                'category_id'  => $this->category->id,
                'status'       => 'available',
            ])
            ->call('create')
            ->assertHasFormErrors(['stock' => 'max']);
    }
}
