<?php

declare(strict_types=1);

namespace Fabric;

use Fabric\Htmx;
use Fabrication\FabricationEngine;

/**
 * Fabric Engine.
 *
 */
class Fabric
{
    public static function version(): float
    {
        return 0.1;
    }
    
    public function getEngine()
    {
        return new FabricationEngine;
    }
}
