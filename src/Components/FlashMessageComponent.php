<?php

namespace Brangerieau\SymfonyCmsBundle\Components;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('flashMessage')]
class FlashMessageComponent
{
    public function __construct(
        private RequestStack $requestStack
    ) {
    }

    public function getMessages()
    {
        return $this->requestStack->getSession()->getFlashBag();
    }
}
