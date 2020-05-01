<?php

namespace App\Action\Admin;

use App\Action\AbstractAction;
use App\Action\SecurityTrait;
use App\Action\TwigTrait;
use App\Entity\Customer;
use App\Form\Type\CreateCustomerType;
use App\Form\Model\CreateCustomer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customers/new", name="app_admin_customer_create")
 */
class CreateCustomerAction extends AbstractAction
{
    use SecurityTrait;
    use TwigTrait;

    private $entityManager;
    private $flashBag;
    private $formFactory;

    public function __construct(EntityManagerInterface $entityManager, FlashBagInterface $flashBag, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessLoggedIn();
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $model = new CreateCustomer();
        $form = $this->formFactory->create(CreateCustomerType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = (new Customer($model->name))
                ->setAddress($model->address)
                ->setPostcode($model->postcode)
                ->setCity($model->city)
                ->setPhone($model->phone)
                ->setEmail($model->email);

            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            $this->flashBag->add('success', 'flash.success.customer.create');

            return $this->redirectToRoute('app_admin_customer_list');
        }

        return $this->renderPage('admin-create-customer', 'app/admin/create_customer.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
