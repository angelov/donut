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

namespace AppBundle\FeatureContexts\Setup;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Behat\Service\Storage\StorageInterface;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Movies\Genre;

class MovieGenresContext implements Context
{
    private $entityManager;
    private $storage;
    private $uuidGenerator;

    public function __construct(EntityManagerInterface $entityManager, StorageInterface $storage, UuidGeneratorInterface $uuidGenerator)
    {
        $this->entityManager = $entityManager;
        $this->storage = $storage;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @Given there are the following genres:
     */
    public function thereAreTheFollowingGenres(TableNode $table) : void
    {
        foreach ($table as $genre) {
            $id = $this->uuidGenerator->generate();

            $genreObj = new Genre($id, $genre['Title']);
            $this->entityManager->persist($genreObj);
            $sg = $this->storage->get('created_genres', []);
            $sg[$genre['Title']] = $genreObj;
            $this->storage->set('created_genres', $sg);
        }

        $this->entityManager->flush();
    }
}
