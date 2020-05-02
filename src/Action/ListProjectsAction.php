<?php

namespace App\Action;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects", name="app_project_list")
 */
class ListProjectsAction
{
    use SecurityTrait;
    use TwigTrait;

    public function __invoke(): Response
    {
        $this->denyAccessUnlessLoggedIn();

        return $this->renderPage('list-projects', 'app/project/list.html.twig', [
            'projects' => $this->getUser()->getProjects()
        ]);
    }
}