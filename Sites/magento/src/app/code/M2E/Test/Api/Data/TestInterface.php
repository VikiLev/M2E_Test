<?php

declare(strict_types=1);

namespace M2E\Test\Api\Data;

interface TestInterface
{

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param string $purchaseDate
     * @return mixed
     */
    public function setPurchaseDate(string $purchaseDate);

    /**
     * @return mixed
     */
    public function getPurchaseDate();

    /**
     * @param string $shipToName
     * @return mixed
     */
    public function setShipToName(string $shipToName);

    /**
     * @return mixed
     */
    public function getShipToName();

    /**
     * @param string $customerEmail
     * @return mixed
     */
    public function setCustomerEmail(string $customerEmail);

    /**
     * @return mixed
     */
    public function getCustomerEmail();

    /**
     * @param float $grantTotal
     * @return mixed
     */
    public function setGrantTotal(float $grantTotal);

    /**
     * @return mixed
     */
    public function getGrantTotal();

    /**
     * @param string $status
     * @return mixed
     */
    public function setStatus(string $status);

    /**
     * @return mixed
     */
    public function getStatus();
}
