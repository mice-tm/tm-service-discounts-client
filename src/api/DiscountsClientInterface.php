<?php
namespace micetm\Clients\ServiceDiscounts\api;

use micetm\Clients\ServiceDiscounts\exceptions\DiscountNotFound;
use micetm\Clients\ServiceDiscounts\exceptions\RequiredFieldsEmpty;
use RuntimeException;
use micetm\Clients\ServiceDiscounts\models\Discount;

interface DiscountsClientInterface
{

    /**
     * @param array $params
     * @return Discount
     *
     * @throws RequiredFieldsEmpty
     * @throws RuntimeException
     */
    public function createNewDiscount($params = array());

    /**
     * @param string $code
     * @return Discount
     *
     * @throws RequiredFieldsEmpty
     * @throws DiscountNotFound
     */
    public function viewDiscount($code);

    /**
     * @param string $code
     * @param array $products
     * @param array $cart
     * @return Discount
     *
     * @throws RequiredFieldsEmpty
     * @throws DiscountNotFound
     */
    public function applyDiscount($code, $products = array(), $cart = array());

    /**
     * @param string $code
     * @return bool
     *
     * @throws RequiredFieldsEmpty
     * @throws RuntimeException
     */
    public function deleteDiscount($code);

    /**
     * @param $code
     * @param array $params
     * @return Discount
     *
     * @throws RequiredFieldsEmpty
     * @throws RuntimeException
     */
    public function updateDiscount($code, $params = array());

    /**
     * @param array $params
     * @return Discount[]
     */
    public function findDiscounts($params = array());
}
