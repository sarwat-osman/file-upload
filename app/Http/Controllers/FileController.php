<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileService;
use Exception;

class FileController extends Controller
{
	public function index () {
		return view('index');
	}

	public function upload (Request $request) {
    	try {

    		if ($request->hasFile('file')) {
    			$file = $request->file('file');	
    			if (!in_array($file->getClientOriginalExtension(), ['csv','xls','xlsx'])) {
    				throw new Exception("Invalid format! Please upload csv or excel.");    				
    			}
    		} else {
    			throw new Exception("File is required!");    			
    		}

    		$fileService = new FileService();
    		$serializedOutput = $fileService->upload($file);

    		return response($serializedOutput, 200)->header('Content-Type', 'application/json');

    	} catch (Exception $e) {
	    	return back()->with('error', $e->getMessage()); 
	    }
    }
}
