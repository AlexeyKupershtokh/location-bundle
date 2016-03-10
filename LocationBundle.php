<?php

namespace AlexeyKuperhstokh\LocationBundle;

use AlexeyKuperhstokh\LocationBundle\DependencyInjection\LocationBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LocationBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new LocationBundleExtension();
    }
}
