<?php
namespace micetm\Clients\ServiceDiscounts\api;

use micetm\Clients\ServiceDiscounts\models\Discount;

interface DiscountsClientInterface
{

    /**
     * @param array $params
     * @return Discount|void
     */
    public function createNewDiscount($params = array());

    /**
     * @param $code
     * @return Discount|void
     */
    public function viewDiscount($code);

    /**
     * @param string $code
     * @param array $products
     * @return Discount|void
     */
    public function applyDiscount($code, $products = array());

    /**
     * @param string $code
     * @return Discount|void
     */
    public function deleteDiscount($code);

    /**
     * @param $code
     * @param array $params
     * @return Discount|void
     */
    public function updateDiscount($code, $params = array());

    /**
     * @param array $params
     * @return Discount[]|void
     */
    public function findDiscounts($params = array());
}
