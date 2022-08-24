<?php

namespace Brangerieau\SymfonyCmsBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisualEditorType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'required' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'visual_editor';
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['label'] = false;
        $view->vars['button'] = $options['label'];
    }

    public function getParent()
    {
        return TextareaType::class;
    }
}
