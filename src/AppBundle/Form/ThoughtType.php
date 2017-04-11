<?php

namespace AppBundle\Form;

use SocNet\Core\Form\DataTransformers\NullToEmptyStringDataTransformer;
use SocNet\Thoughts\Thought;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThoughtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', TextareaType::class);

        $builder->get('content')->addModelTransformer(new NullToEmptyStringDataTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Thought::class);
    }
}
