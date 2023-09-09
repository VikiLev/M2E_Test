<?php

declare(strict_types=1);

namespace M2E\Test\Api;

use M2E\Test\Api\Data\TestInterface;

interface TestRepositoryInterface
{
    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * @return \M2E\Test\Model\Test
     */
    public function getTest();

    /**
     * @param TestInterface $reviews
     * @return mixed
     */
    public function save(TestInterface $test);


    /**
     * @return \M2E\Test\Api\Data\TestInterface[]|\Magento\Framework\DataObject[]
     */
    public function getList();

    /**
     * @param TestInterface $test
     * @return mixed
     */
    public function delete(TestInterface $test);

    /**
     * @param int $id
     * @return mixed
     */
    public function deleteById(int $id);
}
