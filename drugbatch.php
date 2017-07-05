<?php
/**
 * Created by PhpStorm.
 * User: Dan
 * Date: 6/26/2017
 * Time: 12:52 PM
 */

class drugbatch{
    var $batch_number;
    var $serial_number;
    var $arrival;
    var $expire;
    var $arrival_amount;
    var $inventory_balance;
    var $dispensary_balance;
    var $other_department_balance;
    var $total_balance;
    /**
     * @return mixed
     */
    public function getBatch_number()
    {
        return $this->batch_number;
    }

    /**
     * @param mixed $batch_number
     */
    public function setBatch_number($batch_number)
    {
        $this->batch_number = $batch_number;
    }

    /**
     * @return mixed
     */
    public function getArrival()
    {
        return $this->arrival;
    }

    /**
     * @param mixed $arrival
     */
    public function setArrival($arrival)
    {
        $this->arrival = $arrival;
    }


    /**
     * @return mixed
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * @param mixed $expire
     */
    public function setExpire($expire)
    {
        $this->expire = $expire;
    }
    /**
     * @return mixed
     */
    public function getArrival_amount()
    {
        return $this->arrival_amount;
    }

    /**
     * @param mixed $arrival_amount
     */
    public function setArrival_amount($arrival_amount)
    {
        $this->arrival_amount = $arrival_amount;
    }

    /**
     * @return mixed
     */
    public function getInventory_balance()
    {
        return $this->inventory_balance;
    }

    /**
     * @param mixed $inventory_balance
     */
    public function setInventory_balance($inventory_balance)
    {
        $this->inventory_balance = $inventory_balance;
    }

    /**
     * @return mixed
     */
    public function getDispensary_balance()
    {
        return $this->dispensary_balance;
    }

    /**
     * @param mixed $dispensary_balance
     */
    public function setDispensary_balance($dispensary_balance)
    {
        $this->dispensary_balance = $dispensary_balance;
    }

    /**
     * @return mixed
     */
    public function getOther_department_balance()
    {
        return $this->other_department_balance;
    }

    /**
     * @param mixed $other_department_balance
     */
    public function setOther_department_balance($other_department_balance)
    {
        $this->other_department_balance = $other_department_balance;
    }

    /**
     * @return mixed
     */
    public function getTotal_balance()
    {
        return $this->total_balance;
    }

    /**
     * @param mixed $total_balance
     */
    public function setTotal_balance($total_balance)
    {
        $this->total_balance = $total_balance;
    }

}

?>