<?php

namespace Brangerieau\SymfonyCmsBundle;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SymfonyCmsComplierPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasParameter('twig.form.resources')) {
            $resources = $container->getParameter('twig.form.resources') ?: [];
            array_unshift($resources, '@SymfonyCms/type/visual_editor.html.twig');
            $container->setParameter('twig.form.resources', $resources);
        }
    }
}
