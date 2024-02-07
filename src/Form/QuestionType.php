<?php

namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class QuestionType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('question', TextType::class, [
                'label' => 'Question'
            ])
            ->add('file', FileType::class, [
                'label' => 'Fichier audio',
                'mapped' => false,
                'required' => false,
            ])
            ->add('responseType', ChoiceType::class, [
                'label' => 'Type de réponse',
                'choices' => [
                    'Texte' => 'text',
                    'Case à cocher' => 'checkbox'
                ]
            ])
            ->add('desiredAnswer', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'label' => 'Réponses souhaitées'
            ]);
            
    }


    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}