<?php

namespace AlexeyKuperhstokh\LocationBundle\Tests\Location;

use AlexeyKuperhstokh\LocationBundle\Location\Location;

class LocationTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfulCreation()
    {
        $options = [
            "name" => "Eiffel Tower",
            "coordinates" => [
                "lat" => 21.12,
                "long" => 19.56
            ]
        ];

        $loc = new Location($options);
        $this->assertEquals('Eiffel Tower', $loc->getName());
        $this->assertInstanceOf('\AlexeyKuperhstokh\LocationBundle\Location\Coordinates', $loc->getCoordinates());
        $this->assertEquals(21.12, $loc->getCoordinates()->getLat());
        $this->assertEquals(19.56, $loc->getCoordinates()->getLong());
    }

    public function testMissingNameFields()
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\MissingOptionsException');
        $options = [
            "coordinates" => [
                "lat" => 21.12,
                "long" => 19.56
            ]
        ];
        new Location($options);
    }

    public function testMissingCoordinatesField()
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\MissingOptionsException');
        $options = [
            "name" => "Eiffel Tower",
        ];
        new Location($options);
    }

    public function testMissingLatField()
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\MissingOptionsException');
        $options = [
            "name" => "Eiffel Tower",
            "coordinates" => [
                "lat" => 21.12,
            ]
        ];
        new Location($options);
    }

    public function testNameWrongType()
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        $options = [
            "name" => [],
            "coordinates" => [
                "lat" => 21.12,
                "long" => 19.56
            ]
        ];
        new Location($options);
    }

    public function testCoordinatesWrongType()
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        $options = [
            "name" => [],
            "coordinates" => true
        ];
        new Location($options);
    }

    public function testLatWrongType()
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        $options = [
            "name" => 'Eiffel Tower',
            "coordinates" => [
                'lat' => [],
                'long' => 19.56,
            ]
        ];
        new Location($options);
    }

    public function testLongWrongType()
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        $options = [
            "name" => 'Eiffel Tower',
            "coordinates" => [
                'lat' => 19.56,
                'long' => "",
            ]
        ];
        new Location($options);
    }
}
