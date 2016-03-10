<?php

namespace AlexeyKuperhstokh\LocationBundle\Location;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Location
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct($options)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired('name');
        $resolver->setRequired('coordinates');
        $resolver->addAllowedTypes('name', 'string');
        $resolver->setNormalizer(
            'coordinates',
            function (Options $options, $value) {
                return new Coordinates($value);
            }
        );
        $this->options = $resolver->resolve($options);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->options['name'];
    }

    /**
     * @return Coordinates
     */
    public function getCoordinates()
    {
        return $this->options['coordinates'];
    }
}
