<?php

namespace Dizzy\Cashier\Mollie;

use Mollie_API_Object_List;
use Mollie_API_Object_Payment;
use Mollie_API_Object_Payment_Refund;

class Payment
{
    /**
     * @param $payment_id
     * @param array $filters
     * @return Mollie_API_Object_Payment
     */
    public static function get($payment_id, array $filters = [])
    {
        return Mollie::client()->payments->get($payment_id, $filters);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param array $filters
     * @return Mollie_API_Object_List|Mollie_API_Object_Payment[]
     */
    public static function all($offset = 0, $limit = 0, array $filters = [])
    {
        return Mollie::client()->payments->all($offset, $limit, $filters);
    }

    /**
     * @param array $data
     * @param array $filters
     * @return Mollie_API_Object_Payment
     */
    public static function create(array $data, array $filters = [])
    {
        return Mollie::client()->payments->create($data, $filters);
    }

    /**
     * @param Mollie_API_Object_Payment $payment
     * @param array $filters
     * @return Mollie_API_Object_Payment_Refund
     */
    public static function refund(Mollie_API_Object_Payment $payment, $filters = array())
    {
        return Mollie::client()->payments->refund($payment, $filters);
    }

    /**
     * @param $payment_id
     * @return object
     */
    public static function delete($payment_id)
    {
        return Mollie::client()->payments->delete($payment_id);
    }
}