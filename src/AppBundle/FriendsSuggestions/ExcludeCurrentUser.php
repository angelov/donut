<?php

namespace AppBundle\FriendsSuggestions;

use GraphAware\Common\Type\Node;
use GraphAware\Reco4PHP\Filter\Filter;

class ExcludeCurrentUser implements Filter
{
    public function doInclude(Node $input, Node $item)
    {
        return $input->identity() !== $item->identity();
    }
}
