<?php

namespace Brangerieau\SymfonyCmsBundle\Form;

use Brangerieau\SymfonyCmsBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class EditUserType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => $this->translator->trans('Last Name', [], 'symfonycms'),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('Enter your last name', [], 'symfonycms'),
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('firstname', TextType::class, [
                'label' => $this->translator->trans('First Name', [], 'symfonycms'),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('Enter your first name', [], 'symfonycms'),
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans('Email', [], 'symfonycms'),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('Enter your email', [], 'symfonycms'),
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('avatar', FileType::class, [
                'label' => $this->translator->trans('Avatar', [], 'symfonycms'),
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'mapped' => false,
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('save', SubmitType::class, [
                'label' => $this->translator->trans('Save', [], 'symfonycms'),
                'attr' => ['class' => 'btn btn-primary w-md'],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
