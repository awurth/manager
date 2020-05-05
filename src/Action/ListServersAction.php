<?php

namespace App\Action;

use App\Repository\ServerRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/servers", name="app_server_list")
 */
class ListServersAction
{
    use PaginationTrait;
    use SecurityTrait;
    use TwigTrait;

    private $serverRepository;

    public function __construct(ServerRepository $serverRepository)
    {
        $this->serverRepository = $serverRepository;
    }

    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessLoggedIn();

        $pager = $this->paginate($this->getQueryBuilder(), $request);

        return $this->renderPage('list-servers', 'app/servers.html.twig', [
            'servers' => $pager->getCurrentPageResults(),
            'pager' => $pager
        ]);
    }

    private function getQueryBuilder(): QueryBuilder
    {
        return $this->serverRepository->createQueryBuilder('s')
            ->orderBy('s.createdAt', 'DESC');
    }
}