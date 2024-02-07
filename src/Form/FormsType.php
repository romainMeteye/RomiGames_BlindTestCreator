<?php

namespace App\Form;

use App\Entity\User;

use App\Entity\Forms;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class FormsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Title', null, [
                'label' => 'Titre'
            ])
            ->add('Description', null, [
                'label' => 'Description'
            ])
            ->add('expiration_date', null, [
                'label' => 'Date d\'expiration' 
            ])
            ->add('image', FileType::class, [
                'label' => 'Image de présentation',
                'mapped' => false,
                'required' => false,
            ])
            ->add('questions', CollectionType::class, [
                'entry_type' => QuestionType::class,
                'allow_add' => true,
                'by_reference' => false,
                'label' => false,
            ])
            ->add('User', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'required' => false,
                'mapped' => false, //aucune selection
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Forms::class,
        ]);
    }
}
