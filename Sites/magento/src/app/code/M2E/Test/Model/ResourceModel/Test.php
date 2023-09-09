<?php

declare(strict_types=1);

namespace M2E\Test\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Test extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('m2e_test_table', 'id');
        $this->_isPkAutoIncrement = false;
    }
}
