<?php

namespace App\Action\Project;

use App\Action\AbstractAction;
use App\Entity\ProjectEnvironment;
use App\Form\CreateProjectEnvironmentType;
use App\Form\Model\CreateProjectEnvironment;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{slug}/environments/new", name="app_project_environment_create")
 */
class AddProjectEnvironmentAction extends AbstractAction
{
    private $entityManager;
    private $flashBag;
    private $formFactory;
    private $projectRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        FlashBagInterface $flashBag,
        FormFactoryInterface $formFactory,
        ProjectRepository $projectRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->formFactory = $formFactory;
        $this->projectRepository = $projectRepository;
    }

    public function __invoke(Request $request, string $slug): Response
    {
        $this->denyAccessUnlessLoggedIn();

        $project = $this->projectRepository->findOneBy(['slug' => $slug]);

        if (!$project) {
            throw $this->createNotFoundException('Project not found');
        }

        $this->denyAccessUnlessGranted('MEMBER', $project);

        $model = new CreateProjectEnvironment();
        $form = $this->formFactory->create(CreateProjectEnvironmentType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->addEnvironment(
                (new ProjectEnvironment())
                    ->setServer($model->server)
                    ->setName($model->name)
                    ->setPath($model->path)
                    ->setUrl($model->url)
                    ->setDescription($model->description)
            );

            $this->entityManager->persist($project);
            $this->entityManager->flush();

            $this->flashBag->add('success', 'flash.success.project.environment.create');

            return $this->redirectToRoute('app_project_environments', ['slug' => $project->getSlug()]);
        }

        return $this->renderPage('create-project-environment', 'app/project/create_environment.html.twig', [
            'form' => $form->createView()
        ]);
    }
}