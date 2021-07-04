<?php


namespace App\Security;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccessDeniedHandler implements \Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    /**
     * @inheritDoc
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        return new JsonResponse($this->translator->trans('access_denied', domain: 'api'), 403);
    }
}