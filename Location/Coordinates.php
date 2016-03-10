<?php

namespace AlexeyKuperhstokh\LocationBundle\Location;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Coordinates
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
        $resolver->setRequired('lat');
        $resolver->setRequired('long');
        $resolver->addAllowedTypes('lat', array('float', 'int'));
        $resolver->addAllowedTypes('long', array('float', 'int'));
        $this->options = $resolver->resolve($options);
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->options['lat'];
    }

    /**
     * @return float
     */
    public function getLong()
    {
        return $this->options['long'];
    }
}
