<?php
// src/Security/AccessDeniedHandler.php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
{
// ...

return new Response('bundles/TwigBundle/Exception/error403.html.twig', 403);
}
}
