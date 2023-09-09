<?php

declare(strict_types=1);

namespace M2E\Test\Model;

use M2E\Test\Api\Data\TestInterface;
use Magento\Framework\Model\AbstractModel;

class Test extends AbstractModel implements TestInterface
{

    const TABLE_NAME = 'm2e_test_table';

    const ID = 'id';

    const PURCHASE_DATE = 'purchase_date';
    const SHIP_TO_NAME = 'ship_to_name';
    const CUSTOMER_EMAIL = 'customer_email';
    const GRANT_TOTAL = 'grant_total';

    const STATUS = 'status';

    protected function _construct()
    {
        $this->_init(\M2E\Test\Model\ResourceModel\Test::class);
    }


    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @param string $purchaseDate
     * @return $this|mixed
     */
    public function setPurchaseDate(string $purchaseDate)
    {
        $this->setData(self::PURCHASE_DATE, $purchaseDate);

        return $this;
    }


    /**
     * @return array|mixed|null
     */
    public function getPurchaseDate(): mixed
    {
        return $this->getData(self::PURCHASE_DATE);
    }


    public function setShipToName(string $shipToName)
    {
        $this->setData(self::SHIP_TO_NAME, $shipToName);

        return $this;
    }

    public function getShipToName()
    {
        return $this->getData(self::SHIP_TO_NAME);
    }


    public function setCustomerEmail(string $customerEmail)
    {
        $this->setData(self::CUSTOMER_EMAIL, $customerEmail);

        return $this;
    }


    public function getCustomerEmail()
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }


    public function setGrantTotal(float $grantTotal)
    {
        $this->setData(self::GRANT_TOTAL, $grantTotal);

        return $this;
    }


    public function getGrantTotal()
    {
        return $this->getData(self::GRANT_TOTAL);
    }

    public function setStatus(string $status)
    {
        $this->setData(self::STATUS, $status);

        return $this;
    }


    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }
}
