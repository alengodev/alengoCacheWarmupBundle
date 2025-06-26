<?php

declare(strict_types=1);

/*
 * This file is part of Alengo\Bundle\AlengoCacheWarmupBundle.
 *
 * (c) alengo
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Alengo\Bundle\AlengoCacheWarmupBundle;

use Alengo\Bundle\AlengoCacheWarmupBundle\DependencyInjection\AlengoCacheWarmupExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class AlengoCacheWarmupBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new AlengoCacheWarmupExtension();
    }
}
