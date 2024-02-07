<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegisterTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();

        // Vérifier si l'utilisateur existe déjà et le supprimer le cas échéant
        $userRepository = $client->getContainer()->get('doctrine')->getRepository(User::class);
        $existingUser = $userRepository->findOneBy(['username' => 'Test']);

        if ($existingUser) {
            $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
            $entityManager->remove($existingUser);
            $entityManager->flush();
        }

        $crawler = $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Créer le compte')->form([
            'registration_form[username]' => 'Test',
            'registration_form[plainPassword]' => 'password123',
            'registration_form[agreeTerms]' => true,
        ]);

        $client->submit($form);

        // Vérifie la redirection après avoir validé
        $this->assertResponseRedirects('/login');

        $userRepository = $client->getContainer()->get('doctrine')->getRepository(User::class);
        $user = $userRepository->findOneBy(['username' => 'new_user']);

        // Vérifie que l'utilisateur existe
        $this->assertNotNull($user);

        // Nettoyage de la BDD
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->remove($user);
        $entityManager->flush();
    }
}
