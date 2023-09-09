<?php

declare(strict_types=1);

namespace M2E\Test\Model\ResourceModel\Test;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(\M2E\Test\Model\Test::class,
            \M2E\Test\Model\ResourceModel\Test::class);
    }
}
