<?php

namespace App\Services;

use App\ServiceInterfaces\CustomerInterface;

class CustomerService
{
    protected CustomerInterface $customerInterface;

    public function __construct(CustomerInterface $customerInterface)
    {
        $this->customerInterface = $customerInterface;
    }

    public function getCustomerDetails($customerInputData)
    {
        $accountNumber =  $customerInputData['account_number'];
        $customerStatus = $this->customerInterface->getCustomerStatus($accountNumber);
        if ($customerStatus) {
            $customerDetails = $this->customerInterface->getCustomerDetails($accountNumber);
            if (count($customerDetails) > 1) {
                $customerDisplayValue = [
                    'last_reading_date' => $customerDetails[0]['reading_date'],
                    'previous_reading_date' => $customerDetails[1]['reading_date'],
                    'last_reading' => $customerDetails[0]['reading_value'],
                    'previous_reading' => $customerDetails[1]['reading_value'],
                    'fixed_charge' => $customerDetails[0]['fixed_charge'],
                    'first_range_amount' => $customerDetails[0]['first_range_amount'],
                    'second_range_amount' => $customerDetails[0]['second_range_amount'],
                    'third_range_amount' =>  $customerDetails[0]['third_range_amount'],
                    'total_amount' => $customerDetails[0]['total_amount']
                ];
            } else if (count($customerDetails) == 1) {
                $customerDisplayValue = [
                    'last_reading_date' => $customerDetails[0]['reading_date'],
                    'previous_reading_date' => null,
                    'last_reading' => $customerDetails[0]['reading_value'],
                    'previous_reading' => null,
                    'fixed_charge' => $customerDetails[0]['fixed_charge'],
                    'first_range_amount' => $customerDetails[0]['first_range_amount'],
                    'second_range_amount' => $customerDetails[0]['second_range_amount'],
                    'third_range_amount' =>  $customerDetails[0]['third_range_amount'],
                    'total_amount' => $customerDetails[0]['total_amount']
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Customer details does not exist',
                    'data' => null,
                ];
            }
            return [
                'status' => 'success',
                'message' => 'Successfully retrieved data',
                'data' => $customerDisplayValue,
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Invalid account number',
                'data' => null,
            ];
        }
    }
}
