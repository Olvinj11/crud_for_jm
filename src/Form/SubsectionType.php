<?php

namespace App\Form;

use App\Entity\Section;
use App\Entity\Subsection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubsectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('controller')
            ->add('plid')
            ->add('url')
            ->add('position')
            ->add('status')
            ->add('section', EntityType::class, [
                'class' => Section::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subsection::class,
        ]);
    }
}
