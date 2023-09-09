<?php

declare(strict_types=1);

namespace M2E\Test\Model;

use M2E\Test\Api\TestRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use M2E\Test\Model\TestFactory;
use M2E\Test\Model\ResourceModel\Test as ResourceModel;
use M2E\Test\Model\ResourceModel\Test\CollectionFactory;
use M2E\Test\Api\Data\TestInterface;
use M2E\Test\Api\Data\TestInterfaceFactory;
use M2E\Test\Api\Data\TestSearchResultsInterfaceFactory;
use Magento\Framework\App\RequestInterface;

class TestRepository implements TestRepositoryInterface
{
    private TestSearchResultsInterfaceFactory $searchResultFactory;
    private CollectionProcessorInterface $collectionProcessor;
    private CollectionFactory $collectionFactory;
    private ResourceModel $resourceModel;
    private LoggerInterface $logger;
    private TestInterfaceFactory $modelFactory;
    private TestFactory $testFactory;
    private RequestInterface $request;

    /**
     * @param LoggerInterface $logger
     * @param TestInterfaceFactory $testInterfaceFactory
     * @param ResourceModel $resourceModel
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param TestSearchResultsInterfaceFactory $searchResultFactory
     * @param \M2E\Test\Model\TestFactory $testFactory
     * @param RequestInterface $request
     */
    public function __construct(
        LoggerInterface $logger,
        TestInterfaceFactory $testInterfaceFactory,
        ResourceModel $resourceModel,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        TestSearchResultsInterfaceFactory $searchResultFactory,
        TestFactory $testFactory,
        RequestInterface $request
    ) {
        $this->searchResultFactory = $searchResultFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->collectionFactory = $collectionFactory;
        $this->resourceModel = $resourceModel;
        $this->logger = $logger;
        $this->modelFactory = $testInterfaceFactory;
        $this->testFactory = $testFactory;
        $this->request = $request;
    }

    public function getById(int $id): TestInterface
    {
        $model = $this->modelFactory->create();

        $this->resourceModel->load($model, $id);

        if (null === $model->getId()) {
            throw new NoSuchEntityException(__('Model with %1 not found', $id));
        }

        return $model;
    }

    /**
     * @return Test
     */
    public function getTest(): Test
    {
        return$this->testFactory->create();
    }

    /**
     * @return \M2E\Test\Api\Data\TestInterface[]|\Magento\Framework\DataObject[]
     */
    public function getList($shipToName = null, $clientEmail = null, $status = null, $page = null, $limit = null )
    {
        $collection = $this->collectionFactory->create();

        $shipToName = $this->request->getParam('ship_to_name');
        $customerEmail = $this->request->getParam('customer_email');
        $status = $this->request->getParam('status');
        $page = $this->request->getParam('page');
        $pageSize = $this->request->getParam('limit');

        if ($shipToName !== null) {
            $collection->addFieldToFilter('ship_to_name', $shipToName);
        }

        if ($customerEmail !== null) {
            $collection->addFieldToFilter('customer_email', $customerEmail);
        }

        if ($status !== null) {
            $collection->addFieldToFilter('status', $status);
        }

        if ($page !== null) {
            $collection->setCurPage($page);
        }

        if ($pageSize !== null) {
            $collection->setPageSize($pageSize);
        }

        return $collection->getItems();
    }

    /**
     * @param TestInterface $test
     * @return $this|TestRepositoryInterface
     * @throws CouldNotSaveException
     */
    public function save(TestInterface $test): TestRepositoryInterface
    {
        try {
            $this->resourceModel->save($test);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new CouldNotSaveException(__("test not saved"));
        }

        return  $this;
    }

    /**
     * @param TestInterface $test
     * @return $this|TestRepositoryInterface
     * @throws CouldNotDeleteException
     */
    public function delete(TestInterface $test): TestRepositoryInterface
    {
        try {
            $this->resourceModel->delete($test);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new CouldNotDeleteException(__("test %1 not deleted", $test->getId()));
        }
        return  $this;
    }

    /**
     * @param int $id
     * @return $this|TestRepositoryInterface
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $id): TestRepositoryInterface
    {
        try {
            $model = $this->getById($id);
            $this->delete($model);
        } catch (NoSuchEntityException $e) {
            $this->logger->warning(sprintf("test %d already deleted or not found", $id));
        }
        return $this;
    }
}
