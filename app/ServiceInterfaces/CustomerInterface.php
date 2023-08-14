<?php

namespace App\ServiceInterfaces;

interface CustomerInterface
{
    public function getCustomerDetails($accountNumber);

    public function getCustomerStatus($accountNumber);
}
