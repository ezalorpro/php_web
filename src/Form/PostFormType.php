<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titulo',
                'required' => true, 
                'constraints' => [
                    new NotBlank([
                        'message' => 'El titulo es obligatorio.',
                    ]),
                ],
            ])
            ->add('post_text', TextareaType::class, ['label' => 'Contenido','required' => false])
            ->add('tags', HiddenType::class, [
                'label' => 'Tags',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ingrese al menos un tag.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}