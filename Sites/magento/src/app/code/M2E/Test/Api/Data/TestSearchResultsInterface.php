<?php

declare(strict_types=1);

namespace M2E\Test\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;
interface TestSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Magento\Framework\Api\ExtensibleDataInterface[]
     */
    public function getItems();

    /**
     * @param array $items
     * @return TestSearchResultsInterface
     */
    public function setItems(array $items);
}
