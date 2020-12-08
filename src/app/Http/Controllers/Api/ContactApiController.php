<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ImportMapperService;
use App\Models\Contact;

class ContactApiController extends Controller
{
    public $service = NULL;

    public function __construct(ImportMapperService $service) 
    {
        $this->service  = $service;
    }

    public function import(Request $req) {
        $file = $req->file('csvFile');
        $results = $this->service->importContactsFromCsv($file);
        return $results;
    }

    public function list() {
        $contacts = Contact::with('customAttrs')->get();
        return $contacts->toArray();
    }
}
