<?php

namespace Brangerieau\SymfonyCmsBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymfonyCmsBundle extends Bundle
{
    public const VERSION = '0.1.0';

    /**
     * @return void
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new SymfonyCmsComplierPass());
    }

    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
