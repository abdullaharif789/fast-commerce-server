<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $total = null)
    {
        return response()->json($result, 200,['Content-Language'=>$total]);
    }
    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 422)
    {
        if(gettype($errorMessages)=="object")
            return response()->json(implode(' ', $errorMessages->all()), $code);
        else
            return response()->json($error, $code);
    }
}
