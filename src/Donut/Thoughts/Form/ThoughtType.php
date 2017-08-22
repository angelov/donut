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

namespace Angelov\Donut\Thoughts\Form;

use Angelov\Donut\Core\Form\DataTransformers\NullToEmptyStringDataTransformer;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Thoughts\Commands\StoreThoughtCommand;
use Angelov\Donut\Users\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThoughtType extends AbstractType
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
        $builder->add('content', TextareaType::class);

        $builder->get('content')->addModelTransformer(new NullToEmptyStringDataTransformer());
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => StoreThoughtCommand::class,
            'empty_data' => function (FormInterface $form) : StoreThoughtCommand {
                return new StoreThoughtCommand(
                    $this->uuidGenerator->generate(),
                    $this->author,
                    $form->get('content')->getData()
                );
            }
        ]);
    }
}
