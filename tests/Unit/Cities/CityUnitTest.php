<?php

namespace Laracommerce\Core\Tests\Unit\Cities;

use Laracommerce\Core\Cities\Exceptions\CityNotFoundException;
use Laracommerce\Core\Cities\Repositories\CityRepository;
use Laracommerce\Core\Cities\City;
use Laracommerce\Core\Tests\TestCase;

class CityUnitTest extends TestCase
{
    /** @test */
    public function it_can_list_all_the_cities()
    {
        $city = factory(City::class)->create();
        $cityRepo = new CityRepository($city);

        $this->assertCount(1, $cityRepo->listCities());
    }
    
    /** @test */
    public function it_can_update_the_city()
    {
        $city = factory(City::class)->create();
        $cityRepo = new CityRepository($city);

        $update = ['name' => 'Manila'];
        $cityRepo->updateCity($update);

        $this->assertEquals($update['name'], $city->name);
    }
    
    /** @test */
    public function it_will_error_when_city_is_not_found()
    {
        $this->expectException(CityNotFoundException::class);

        $cityRepo = new CityRepository(new City);
        $cityRepo->findCityById(999);
    }

    /** @test */
    public function it_can_find_the_city()
    {
        $city = factory(City::class)->create();
        $cityRepo = new CityRepository(new City);
        $found = $cityRepo->findCityById($city->id);

        $this->assertEquals($city->name, $found->name);
    }
}
