<?php

namespace App\Action\Credentials;

use App\Action\AbstractAction;
use App\Action\SecurityTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/credentials", name="app_credentials_list")
 */
class ListCredentialsAction extends AbstractAction
{
    use SecurityTrait;

    public function __invoke(): Response
    {
        $this->denyAccessUnlessLoggedIn();

        return $this->renderPage('list-credentials', 'app/credentials/list.html.twig', [
            'credentials' => $this->getUser()->getCredentialsList()
        ]);
    }
}