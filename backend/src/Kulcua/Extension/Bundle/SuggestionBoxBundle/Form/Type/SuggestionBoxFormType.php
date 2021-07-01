<?php

namespace Kulcua\Extension\Bundle\SuggestionBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Kulcua\Extension\Bundle\SuggestionBoxBundle\Validator\Constraints;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class SuggestionBoxFormType.
 */
class SuggestionBoxFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('senderId', TextType::class, [
            'required' => true
        ]);
        $builder->add('senderName', TextType::class, [
            'required' => true
        ]);
        $builder->add('problemType', TextType::class, [
            'required' => true
        ]);
        $builder->add('description', TextType::class, [
            'required' => true
        ]);
        $builder->add(
            'active',
            CheckboxType::class,
            [
                'required' => false,
            ]
        );
        $builder->add(
            'photo',
            FileType::class,
            [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Constraints\Image(
                        [
                            'mimeTypes' => ['image/png', 'image/gif', 'image/jpeg', 'image/jpg', 'video/mp4'],
                            'maxSize' => '20M',
                        ]
                    ),
                ],
            ]
        );
        $builder->add('timestamp', DateTimeType::class, [
            'required' => true,
            'widget' => 'single_text',
            'format' => DateTimeType::HTML5_FORMAT,
            'constraints' => [
                new NotBlank(),
            ],
        ]);
    }
}
