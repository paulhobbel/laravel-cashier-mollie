<?php

namespace Dizzy\Cashier\Mollie;


use Mollie_API_Object_Customer_Subscription;
use Mollie_API_Object_List;

class Subscription
{
    /**
     * @param $subscription_id
     * @param array $filters
     * @return Mollie_API_Object_Customer_Subscription
     */
    public static function get($subscription_id, array $filters = [])
    {
        return Mollie::client()->customers_subscriptions->get($subscription_id, $filters);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param array $filters
     * @return Mollie_API_Object_Customer_Subscription[]|Mollie_API_Object_List
     */
    public static function all($offset = 0, $limit = 0, array $filters = [])
    {
        return Mollie::client()->customers_subscriptions->all($offset, $limit, $filters);
    }

    /**
     * @param array $data
     * @param array $filters
     * @return Mollie_API_Object_Customer_Subscription
     */
    public static function create(array $data, array $filters = [])
    {
        return Mollie::client()->customers_subscriptions->create($data, $filters);
    }

    /**
     * @param $subscription_id
     * @return Mollie_API_Object_Customer_Subscription
     */
    public static function cancel($subscription_id)
    {
        return Mollie::client()->customers_subscriptions->cancel($subscription_id);
    }

    /**
     * @param $subscription_id
     * @return Mollie_API_Object_Customer_Subscription
     */
    public static function delete($subscription_id)
    {
        return Mollie::client()->customers_subscriptions->delete($subscription_id);
    }
}