<?php

namespace App\Action\Project;

use App\Action\AbstractAction;
use App\Action\RoutingTrait;
use App\Action\SecurityTrait;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{slug}/delete", name="app_project_delete")
 */
class DeleteProjectAction extends AbstractAction
{
    use RoutingTrait;
    use SecurityTrait;

    private $entityManager;
    private $flashBag;
    private $projectRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        FlashBagInterface $flashBag,
        ProjectRepository $projectRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->projectRepository = $projectRepository;
    }

    public function __invoke(Request $request, string $slug): Response
    {
        $this->denyAccessUnlessLoggedIn();

        $project = $this->projectRepository->findOneBy(['slug' => $slug]);

        if (!$project) {
            throw $this->createNotFoundException('Project not found');
        }

        $this->denyAccessUnlessGranted('DELETE', $project);

        $this->entityManager->remove($project);
        $this->entityManager->flush();

        $this->flashBag->add('success', 'flash.success.project.delete');

        return $this->redirectToRoute('app_project_list');
    }
}
