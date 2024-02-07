<?php

namespace App\Controller;

use App\Entity\Forms;
use App\Form\FormsType;
use App\Repository\FormsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/forms')]
class FormsController extends AbstractController
{
    #[Route('/', name: 'app_forms_index', methods: ['GET'])]
    public function index(FormsRepository $formsRepository): Response
    {
        return $this->render('forms/index.html.twig', [
            'forms' => $formsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_forms_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $form = $this->createForm(FormsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $user = $this->getUser();
            $imageFile = $form['image']->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = preg_replace('/\s+/', '-', trim(strtolower($originalFilename)));
                $newFilename = $safeFilename . '-' . $formData->getTitle() . '-thumbnail.' . $imageFile->guessExtension();
        
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
        
                $formData->setImagePath($newFilename);
            }
            if ($user) {
                $email = (new Email())
                    ->from('rominus.27000@gmail.com')
                    ->to($user->getEmail())
                    ->subject('Votre formulaire a été créé')
                    ->text("Le nouveau Blind Test que vous avez créé à été plublier sur le site avec succès ! N'hésitez pas à le partager à vos amis !");
    
                $mailer->send($email);
            }

            $formData->setUser($user);

            $entityManager->persist($formData);
            $entityManager->flush();

            return $this->redirectToRoute('app_forms_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forms/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_forms_show', methods: ['GET'])]
    public function show(Forms $form): Response
    {
        return $this->render('forms/show.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_forms_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Forms $form, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormsType::class, $form); // Utilisez l'instance de FormsType pour éditer un formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('app_forms_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forms/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_forms_delete', methods: ['POST'])]
    public function delete(Request $request, Forms $forms, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forms->getId(), $request->request->get('_token'))) {
            $entityManager->remove($forms);
            $entityManager->flush();
        }

        // Redirection après la suppression
        return $this->redirectToRoute('app_forms_index');
    }
}
