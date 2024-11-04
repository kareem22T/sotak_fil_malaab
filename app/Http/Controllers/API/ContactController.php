<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function postMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(['status' => false, 'msg' => $firstError, 'notes' => ['Invalid data']], 400);
        }

        $message = Contact::create($request->toArray());


        return response()->json(['status' => true, 'msg' => 'Message sent successfully', 'data' => ['message' => $message], 'notes' => ['Message sent successfully']], 201);
    }


}
