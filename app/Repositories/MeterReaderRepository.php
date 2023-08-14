<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\CustomerMeterReading;
use App\Models\MeterReader;
use App\ServiceInterfaces\MeterReaderInterface;

class MeterReaderRepository implements MeterReaderInterface
{

    public function login($readerId)
    {
        try {
            $meterReader = MeterReader::where('reader_id', '=', $readerId)->exists();

            if ($meterReader) {
                return MeterReader::where('reader_id', '=', $readerId)->first();
            }else{
                return false;
            }
        } catch (\Exception $e){
            return $e->getCode();
        }
    }

    public function addCustomerDetails($customerDetails)
    {
//        try {
            $newCustomerDetails = new CustomerMeterReading();
            $newCustomerDetails->fill($customerDetails);
            $newCustomerDetails->save();
            return true;
        /*} catch (\Exception $e){
            return $e->getCode();
        }*/
    }

    public function getCustomerStatus($accountNumber)
    {
        try {
            return Customer::where('account_number', '=', $accountNumber)->exists();
        } catch (\Exception $e){
            return $e->getCode();
        }
    }

    public function getCustomerPreviousDetails($accountNumber)
    {
        try {
            return CustomerMeterReading::where('account_number', '=', $accountNumber)->orderBy('reading_date', 'desc')->first();
        } catch (\Exception $e){
            return $e->getCode();
        }
    }
}
