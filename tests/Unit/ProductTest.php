<?php

namespace Tests\Unit;

use App\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductTest extends TestCase
{
    protected $product;

    public function setUp()
    {
        $this->product = new Product('Fallout 4', 85);
    }

    /** @test */
    function aProductHasName()
    {
        $this->assertEquals('Fallout 4', $this->product->name());
    }

    /** @test */
    public function aProductHasPrice()
    {
        $this->assertEquals(85, $this->product->price());
    }
}
