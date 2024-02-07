<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormsTest extends WebTestCase
{
    public function testFormSubmission()
{
    $client = static::createClient();
    $crawler = $client->request('GET', '/forms/new');

    $submitButton = $crawler->filter('button:contains("Save")');

    // Simule le clic sur le bouton
    $form = $submitButton->form();

    // Données simulées
    $form['forms[Title]'] = 'Test Title';
    $form['forms[Description]'] = 'Test Description';

    // utilisateur de test existant de la base de données
    $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->findOneBy(['username' => 'romi']);

    if ($user) {
        // Assigne l'utilisateur au champ forms[User]
        $form['forms[User]']->setValue($user->getId());
    } else {
        // message d'erreur si l'utilisateur de test n'a pas été trouvé
        $this->fail('L\'utilisateur de test n\'a pas été trouvé.');
    }

    $client->submit($form);

    // Vérifie si la soumission a réussi et la redirigé vers une autre page
    $this->assertResponseRedirects('/forms');

    // Vérifie les données enregistrées dans la base de données
    $formEntity = $entityManager->getRepository('App\Entity\Forms')->findOneBy(['Title' => 'Test Title']);
    $this->assertNotNull($formEntity);
    $this->assertEquals('Test Description', $formEntity->getDescription());
}

}