<?php

namespace App\Services;

use App\ServiceInterfaces\MeterReaderInterface;
use ErrorException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Psy\Exception\TypeErrorException;

class MeterReaderService
{
    protected MeterReaderInterface $meterReaderInterface;

    public function __construct(MeterReaderInterface $meterReaderInterface)
    {
        $this->meterReaderInterface = $meterReaderInterface;
    }

    public function login($loginData): array
    {
        $meterReader = $this->meterReaderInterface->login($loginData['readerId']);
        if($meterReader && Hash::check($loginData['password'],$meterReader['password'])){
//            $token = $meterReader->createToken('token')->plainTextToken;
            return [
                'status' => true,
                'message' => 'Successfully logged in',
                'data' => $meterReader,
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Login failed',
                'data' => null,
            ];
        }
    }

    public function addMeterReadings($customerInputDetails)
    {
        $accountNumber =  $customerInputDetails['account_number'];
        $customerStatus = $this->meterReaderInterface->getCustomerStatus($accountNumber);

        if ($customerStatus){
            $customerPreviousDetails = $this->meterReaderInterface->getCustomerPreviousDetails($accountNumber);
            $customerPreviousValues = $this->assignValues($customerPreviousDetails);
            $calculateValues = $this->calculateMeterReadings($customerInputDetails , $customerPreviousValues);
            if($calculateValues == null){
                return [
                    'status' => false,
                    'message' => 'current meter reading value should not be less than previous reading value',
                    'data' => '',
                ];
            }

            $customerDetails = [
                'account_number' => $customerInputDetails['account_number'],
                'reading_date' => $customerInputDetails['reading_date'],
                'reading_value' => $customerInputDetails['reading_value'],
                'fixed_charge' => $calculateValues['fixed_charge'],
                'first_range_amount' => $calculateValues['first_range_amount'],
                'second_range_amount' => $calculateValues['second_range_amount'],
                'third_range_amount' =>  $calculateValues['third_range_amount'],
                'total_amount' => $calculateValues['total_amount']
            ];
            $addCustomerDetailsStatus = $this->meterReaderInterface->addCustomerDetails($customerDetails);

            return [
                'status' => $addCustomerDetailsStatus,
                'message' => 'Successfully Added Meter Readings',
                'data' => $customerDetails,
            ];

        }else {
            return [
                'status' => false,
                'message' => 'Customer does not exist',
                'data' => null,
            ];
        }
    }

    public function assignValues($customerPreviousDetails)
    {
        if ($customerPreviousDetails == null){
            $customerPreviousValues = [
                'previous_reading_date' => Carbon::now()->toDateString(),
                'previous_reading_value' => 0
            ];
        } else {
            $customerPreviousValues = [
                'previous_reading_date' => $customerPreviousDetails['reading_date'],
                'previous_reading_value' => $customerPreviousDetails['reading_value'],
            ];
        }
        return $customerPreviousValues;
    }

    private function calculateMeterReadings($customerInputDetails,$customerPreviousValues)
    {
        $readingDifference = $customerInputDetails['reading_value'] - $customerPreviousValues['previous_reading_value'];

        if($readingDifference>0){
            $readingDate = Carbon::parse($customerInputDetails['reading_date']);
            $previousReadingDate = Carbon::parse($customerPreviousValues['previous_reading_date']);

            $dateDifference = $readingDate->diffInDays($previousReadingDate);

            $unitsSecondRange = 0;

            $unitsFirstRange = $dateDifference;

            if( $readingDifference > $unitsFirstRange){
                $unitsSecondRange = 2 * $dateDifference;
            }

            $unitsThirdRange = $readingDifference - ($unitsFirstRange + $unitsSecondRange);

            if($readingDifference<= $unitsFirstRange){
                $fixedCharge = 500;
            }else{
                $fixedCharge = 1000;
            }

            $firstRangeAmount = $unitsFirstRange * 20;
            $secondRangeAmount = $unitsSecondRange * 35;
            $thirdRangeAmount = 0;


            if ($unitsThirdRange > 0) {
                $fixedCharge = 1500;
                $perUnitCharge = 40;

                for ($i = 0; $i < $unitsThirdRange; $i++) {
                    $thirdRangeAmount += $perUnitCharge;
                    $perUnitCharge++;
                }
            }

            $totalAmount = $firstRangeAmount + $secondRangeAmount + $thirdRangeAmount + $fixedCharge;

            return [
                'first_range_amount' => $firstRangeAmount,
                'second_range_amount' => $secondRangeAmount,
                'third_range_amount' => $thirdRangeAmount,
                'fixed_charge' => $fixedCharge,
                'total_amount' => $totalAmount,
            ];
        }else{
            return null;
        }

    }
}
