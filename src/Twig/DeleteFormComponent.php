<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'DeleteForm')]
class DeleteFormComponent
{
    public string $path;
    public mixed $entityId;
    public string $text = 'supprimer';
    public string $class = 'button danger';
    public string $warning = 'Êtes-vous sûr de bien vouloir supprimer cet élément ?';
    public bool $turbo = true;

    /**
     * Summary of __construct
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @throws \Exception
     * @throws \Symfony\Component\HttpFoundation\Exception\SessionNotFoundException
     */
    public function __construct(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        if (null === $request) {
            throw new \Exception('The request stack is empty');
        }
        $session = $request->getSession();
        $session->set('delete_request_origin_uri', $request->getRequestUri());
    }
}
