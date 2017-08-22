<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace Angelov\Donut\Communities\Form;

use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Users\User;
use Angelov\Donut\Communities\Commands\StoreCommunityCommand;
use Angelov\Donut\Core\Form\DataTransformers\NullToEmptyStringDataTransformer;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommunityType extends BaseType
{
    private $author;
    private $uuidGenerator;

    public function __construct(User $author, UuidGeneratorInterface $uuidGenerator)
    {
        $this->author = $author;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder->add('name', TextType::class);
        $builder->add('description', TextareaType::class, [
            'required' => false
        ]);

        $transformer = new NullToEmptyStringDataTransformer();

        $builder->get('name')->addModelTransformer($transformer);
        $builder->get('description')->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $uuidGenerator = $this->uuidGenerator;

        $resolver->setDefaults([
            'data_class' => StoreCommunityCommand::class,
            'empty_data' => function (FormInterface $form) use ($uuidGenerator) : StoreCommunityCommand {
                return new StoreCommunityCommand(
                    $uuidGenerator->generate(),
                    $form->get('name')->getData(),
                    $this->author,
                    $form->get('description')->getData()
                );
            }
        ]);
    }
}
