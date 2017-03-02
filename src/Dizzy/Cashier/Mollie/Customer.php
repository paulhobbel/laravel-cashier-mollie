<?php

namespace Dizzy\Cashier\Mollie;


class Customer
{
    /**
     * @param array $data
     * @param array $filters
     * @return \Mollie_API_Object_Customer
     */
    public static function create(array $data, array $filters = [])
    {
        return Mollie::client()->customers->create($data, $filters);
    }

    /**
     * @param $id
     * @param array $filters
     * @return \Mollie_API_Object_Customer
     */
    public static function get($id, array $filters = [])
    {
        return Mollie::client()->customers->get($id, $filters);
    }

    /**
     * @param \Mollie_API_Object_Customer $customer
     * @return \Mollie_API_Object_Customer
     */
    public static function update(\Mollie_API_Object_Customer $customer)
    {
        return Mollie::client()->customers->update($customer);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param array $filters
     * @return \Mollie_API_Object_Customer[]|\Mollie_API_Object_List
     */
    public static function all($offset = 0, $limit = 0, array $filters = [])
    {
        return Mollie::client()->customers->all($offset, $limit, $filters);
    }
}