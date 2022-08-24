<?php

namespace Brangerieau\SymfonyCmsBundle\Form;

use Brangerieau\SymfonyCmsBundle\Entity\Pages;
use Brangerieau\SymfonyCmsBundle\Type\VisualEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PagesType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('Name', [], 'symfonycms'),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('Enter page name', [], 'symfonycms'),
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('content', VisualEditorType::class, [
                'label' => $this->translator->trans('Display editor', [], 'symfonycms'),
                'attr' => ['class' => 'btn btn-primary w-md mb-3'],
            ])
            ->add('shortContent', TextareaType::class, [
                'label' => $this->translator->trans('Short content', [], 'symfonycms'),
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('Enter short page content', [], 'symfonycms'),
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('metaDescription', TextareaType::class, [
                'label' => $this->translator->trans('Meta description', [], 'symfonycms'),
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('Enter page meta description', [], 'symfonycms'),
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('visible', CheckboxType::class, [
                'label' => $this->translator->trans('Visible', [], 'symfonycms'),
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'row_attr' => ['class' => 'form-check form-switch form-switch-md mb-3'],
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
            'data_class' => Pages::class,
        ]);
    }
}
