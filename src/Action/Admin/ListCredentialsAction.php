<?php

namespace App\Action\Admin;

use App\Action\AbstractAction;
use App\Action\SecurityTrait;
use App\Repository\CredentialsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/credentials", name="app_admin_credentials_list")
 */
class ListCredentialsAction extends AbstractAction
{
    use SecurityTrait;

    private $credentialsRepository;

    public function __construct(CredentialsRepository $credentialsRepository)
    {
        $this->credentialsRepository = $credentialsRepository;
    }

    public function __invoke(): Response
    {
        $this->denyAccessUnlessLoggedIn();
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $credentials = $this->credentialsRepository->findAll();

        return $this->renderPage('admin-list-credentials', 'app/admin/list_credentials.html.twig', [
            'credentials' => $credentials
        ]);
    }
}