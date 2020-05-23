<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, [
                'required' => true,
                'label' => 'Buscar',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Escriba una busqueda',
                    ]),
                ],
            ])
            ->add('query_type', ChoiceType::class, [
                'label' => 'Buscar por:',
                'required' => true,
                'multiple' => true,
                'expanded' => false,
                'choices'  => [
                    'Titulo' => 'Title',
                    'Tags' => 'Tags',
                    'Usuario' => 'User',
                ],
                'data' => [
                    'Titulo' => 'Title',
                    'Tags' => 'Tags',
                    'Usuario' => 'User',
                ]
            ])
        ;
    }
}