<?php

namespace App\Http\Controllers\Api;

use App\Models\Children;
use App\Models\Location;
use App\Models\Tutor;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;

class LocationController extends BaseController
{
    /**
     * This endpoint is for store coordinates of a kid
     */
    public function index($id)
    {
        $kid = Children::findOrFail($id);
        $tutor = Tutor::find($kid->tutor_id);
        
        if (Auth::user()->id == $tutor->id) {
            $locations = Location::where(['children_id' => $kid->id])->get();

            return $this->sendResponse($locations, "List of locations");
        }

        return $this->sendError("No Content.", 204);
    }

    /**
     * This endpoint is for save coordinates send from app kid
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coordinates' => 'required|string|max:800',
            'date' => 'required|date',
            'time' => 'required',
            'children_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $kid = Children::findOrFail($request->id);
        $tutor = Tutor::find($kid->tutor_id);

        if (Auth::user()->id == $tutor->id) {
           /* $location = Location::create([
                'coordinates' => $request->coordinates,
                'date' => $request->date,
                'time' => $request->time,
                'children_id' => $kid->id
            ]);*/

            return $this->sendResponse($tutor, "Location saved successfully");
        }

        return $this->sendError("No Content.", 204);
    }


    
}
