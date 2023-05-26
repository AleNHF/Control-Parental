<?php

namespace App\Http\Controllers\Api;

use App\Models\Children;
use App\Models\Contacts;
use App\Models\Tutor;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;

class ContactsController extends BaseController
{
    /**
     * This endpoint is for contacts list into the app tutor
     */
    public function getContactsXKid($id) 
    {
        $kid = Children::findOrFail($id);
        $tutor = Tutor::findOrFail($kid->tutor_id);

        if (Auth::user()->id == $tutor->user_id) {
            $contacts = $kid->contacts;

            return $this->sendResponse($contacts, "Contacts list of your kid.");
        } else {
            return $this->sendError("No Content.", 204);
        }       
    }

    /**
     * This endpoint is for store contacts
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'phoneNumber' => 'required|string|max:500',
            'children_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $contact = new Contacts();
        $contact->name = $request->name;
        $contact->phoneNumber = $request->phoneNumber;
        $contact->children_id = $request->children_id;
        $contact->save();

        return $this->sendResponse($contact, "File saved successfully.");
    }

    /**
     * This endpoint is for update contact
     */
    public function update(Request $request, $id)
    {
        $contact = Contacts::findOrFail($id);
        
        if (isset($contact)) {
            $contact->update($request->all());

            return $this->sendResponse($contact, "Contact updated successfully.");
        }

        return $this->sendError("Not Found.", 404);
    }

    /**
     * This endpoint is for show contact
     */
    public function show($id)
    {
        $contact = Contacts::findOrFail($id);

        if (isset($contact)) {
            return $this->sendResponse($contact, "Contact found");
        }

        return $this->sendError("Not Found.", 404);
    }

    /**
     * This endpoint is for delete
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (isset($user)) {
            $contact = Contacts::findOrFail($id);
            if (isset($contact)) {
                $contact->delete();
                return $this->sendResponse($contact, "Contact has deleted.");
            }

            return $this->sendError("Not Found.", 404);
        }

        return $this->sendError("Unauthorized", 401);
    }
}
