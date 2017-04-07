<?php

namespace SocNet\Communities\Form;

use AppBundle\Entity\User;
use SocNet\Communities\Commands\StoreCommunityCommand;
use SocNet\Core\Form\DataTransformers\NullToEmptyStringDataTransformer;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommunityType extends BaseType
{
    private $author;

    public function __construct(User $author)
    {
        $this->author = $author;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);
        $builder->add('description', TextareaType::class, [
            'required' => false
        ]);

        $transformer = new NullToEmptyStringDataTransformer();

        $builder->get('name')->addModelTransformer($transformer);
        $builder->get('description')->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StoreCommunityCommand::class,
            'empty_data' => function (FormInterface $form) {
                return new StoreCommunityCommand(
                    $form->get('name')->getData(),
                    $this->author,
                    $form->get('description')->getData()
                );
            }
        ]);
    }
}
