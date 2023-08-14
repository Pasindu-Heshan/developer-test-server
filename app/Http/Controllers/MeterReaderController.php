<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Services\MeterReaderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MeterReaderController extends Controller
{
    protected MeterReaderService $meterReaderService;

    protected ResponseHelper $responseHelper;

    public function __construct(MeterReaderService $meterReaderService, ResponseHelper $responseHelper)
    {
        $this->meterReaderService = $meterReaderService;
        $this->responseHelper = $responseHelper;
    }

    public function login(Request $request)
    {
        $loginStatus =  $this->meterReaderService->login($request->all());
        switch ($loginStatus['status']) {
            case true:
                return $this->responseHelper->response('success','Successfully logged in', $loginStatus['data'],Response::HTTP_OK);
            case false:
                return $this->responseHelper->response('failed','Unauthorized error', $loginStatus['data'],Response::HTTP_UNAUTHORIZED);
        }
    }

    public function addMeterReading(Request $request)
    {
        $meterReadingStatus = $this->meterReaderService->addMeterReadings($request->all());
        switch ($meterReadingStatus['status']) {
            case true:
                return $this->responseHelper->response('success','Successfully added customer data', $meterReadingStatus['data'],Response::HTTP_OK);
            case false:
                return $this->responseHelper->response('failed',$meterReadingStatus['message'], $meterReadingStatus['data'],Response::HTTP_UNAUTHORIZED);
        }
    }


}
