<?php

namespace Laracommerce\Core\Tests\Unit\ProductAttributes;

use Laracommerce\Core\Attributes\Attribute;
use Laracommerce\Core\Attributes\Repositories\AttributeRepository;
use Laracommerce\Core\AttributeValues\AttributeValue;
use Laracommerce\Core\AttributeValues\Repositories\AttributeValueRepository;
use Laracommerce\Core\ProductAttributes\Exceptions\ProductAttributeNotFoundException;
use Laracommerce\Core\ProductAttributes\ProductAttribute;
use Laracommerce\Core\ProductAttributes\Repositories\ProductAttributeRepository;
use Laracommerce\Core\Products\Product;
use Laracommerce\Core\Products\Repositories\ProductRepository;
use Laracommerce\Core\Tests\TestCase;

class ProductAttributeUnitTest extends TestCase
{
    /** @test */
    public function it_throws_error_when_the_product_attribute_is_not_found()
    {
        $this->expectException(ProductAttributeNotFoundException::class);

        $productAttributeRepo = new ProductAttributeRepository(new ProductAttribute);
        $productAttributeRepo->findProductAttributeById(999);
    }
    
    /** @test */
    public function it_can_find_the_product_attribute_by_id()
    {
        $productAttribute = factory(ProductAttribute::class)->create([
            'product_id' => $this->product->id
        ]);

        $productAttributeRepo = new ProductAttributeRepository(new ProductAttribute);
        $found = $productAttributeRepo->findProductAttributeById($productAttribute->id);

        $this->assertEquals($productAttribute->quantity, $found->quantity);
        $this->assertEquals($productAttribute->price, $found->price);
    }

    /** @test */
    public function it_can_sync_the_attribute_values_to_product_attributes()
    {
        $attribute = factory(Attribute::class)->create(['name' => 'Color']);

        $attributeValueRepo = new AttributeValueRepository(new AttributeValue);
        $created = $attributeValueRepo->createAttributeValue($attribute, ['value' => 'green']);

        $attributeRepo = new AttributeRepository($attribute);
        $associated = $attributeRepo->associateAttributeValue($created);

        $this->assertInstanceOf(AttributeValue::class, $created);
        $this->assertInstanceOf(AttributeValue::class, $associated);
        $this->assertEquals($created->name, $associated->name);
    }

    /** @test */
    public function it_returns_null_deleting_non_existing_product_attribute()
    {
        $product = factory(Product::class)->create();
        $productRepo = new ProductRepository($product);
        $deleted = $productRepo->removeProductAttribute(new ProductAttribute);

        $this->assertNull($deleted);
    }

    /** @test */
    public function it_can_remove_product_attribute()
    {
        $data = [
            'quantity' => 1,
            'price' => 10.45
        ];

        $productAttribute = new ProductAttribute($data);

        $product = factory(Product::class)->create();
        $productRepo = new ProductRepository($product);
        $created = $productRepo->saveProductAttributes($productAttribute);

        $deleted = $productRepo->removeProductAttribute($created);

        $this->assertTrue($deleted);
    }

    /** @test */
    public function it_can_create_product_attribute()
    {
        $data = [
            'quantity' => 1,
            'price' => 10.45
        ];

        $productAttribute = new ProductAttribute($data);

        $product = factory(Product::class)->create();
        $productRepo = new ProductRepository($product);
        $created = $productRepo->saveProductAttributes($productAttribute);

        $this->assertInstanceOf(ProductAttribute::class, $created);
        $this->assertInstanceOf(Product::class, $productAttribute->product);

        $this->assertEquals($data['quantity'], $created->quantity);
        $this->assertEquals($data['price'], $created->price);
        $this->assertEquals($product->name, $created->product->name);
        $this->assertEquals($product->price, $created->product->price);
    }
}
