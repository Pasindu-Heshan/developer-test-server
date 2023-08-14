<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    protected CustomerService $customerService;
    protected ResponseHelper $responseHelper;

    public function __construct(CustomerService $customerService , ResponseHelper $responseHelper)
    {
        $this->customerService = $customerService;
        $this->responseHelper = $responseHelper;
    }

    public function getCustomerDetails(Request $request)
    {
        $getCustomerDetailsStatus = $this->customerService->getCustomerDetails($request->all());
        switch ($getCustomerDetailsStatus['status']) {
            case true:
                return $this->responseHelper->response('success',$getCustomerDetailsStatus['message'], $getCustomerDetailsStatus['data'],Response::HTTP_OK);
            case false:
                return $this->responseHelper->response('failed',$getCustomerDetailsStatus['message'], $getCustomerDetailsStatus['data'],Response::HTTP_OK);
        }
    }
}
