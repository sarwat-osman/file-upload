<?php

namespace App\Services;

use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;
use Exception;

class FileService
{
	private $rootPath;
	private $uploadDir;		//file upload directory

	public function __construct () {
		$this->rootPath = "/";
		$this->uploadDir = storage_path('app/public/');
	}

	/**
     * Takes imported CSV rows collection and re-structures data with timestamp and group id into a new array 
     *
     * @param  collection $records contains original columns of data
     * @return array $array contains transaction datetime, generated timestamp and group id
     */
	public static function rearrangeInput($records) {
		
		try {

			$item = array();
	    	$array = array();

	    	foreach ($records as $key => $record) {
				$item['sl'] = (int) $record['Sl'];

			    $date = Carbon::createFromFormat('d/m/y', $record['Date'])->format('Y-m-d');
			    $dateWithoutSpaces = Carbon::createFromFormat('d/m/y', $record['Date'])->format('Ymd');
			    $item['timestamp'] = Carbon::parse($date . " " . $record['Time'])->timestamp;
			    $item['transaction_at'] = Carbon::parse($date . " " . $record['Time'])->format('Y-m-d H:i:s');

			    $item['product'] = $record['Product'];
			    $item['qty'] = (int) $record['Qty'];
			    $item['price'] = (float) $record['Exe Price'];
			    $item['side'] = $record['Side'];
			    $item['acct'] = $record['Acct'];
			    $item['group'] = $record['Acct'] . "_" . $record['Product'] . "_" . $dateWithoutSpaces;
			    
			    $array[] = $item;	    		
			}

			return $array;

		} catch (Exception $e) {
	    	return $e->getMessage(); 
	    }
	}

	/**
     * Takes a collection of grouped data and creates arrays of 'BOT' and 'SOLD' subgroups in preparation for
     * final serialized json output 
     *
     * @param  collection $groupedRecords sorted data grouped by group id
     * @return array $serializedArray array of 'BOT' and 'SOLD' subgroups
     */
	public static function serializeData($groupedRecords) {
		
		try {

			$serializedArray = array();

			foreach ($groupedRecords as $key1 => $group) {

				$sld = 0;
				$bot = 0;
				$formattedGroup = array();
				$botGroup = array();
				$sldGroup = array();
				$grpArray = $group->toArray();

				foreach ($group as $key2 => $record) {
					if($record['side'] == "BOT") {
						$bot += $record['qty'];
						$botGroup[] = $record; 
					} else {
						$sld += $record['qty'];
						$sldGroup[] = $record;
					}

					if($bot == $sld) {
						$formattedGroup[] = array(
							'bot'=>$botGroup, 
							'sold'=>$sldGroup, 
							'botQty'=>$bot, 
							'soldQty'=>$sld, 
							'closed'=>true
						);
						$botGroup = array();
						$sldGroup = array();
						$sld = 0;
						$bot = 0;
					} else if($record == end($grpArray)) {
						$formattedGroup[] = array(
							'bot'=>$botGroup, 
							'sold'=>$sldGroup, 
							'botQty'=>$bot, 
							'soldQty'=>$sld, 
							'closed'=>false
						);
					}
				}

				$serializedArray[$key1] = $formattedGroup;
			}

			return $serializedArray;

		} catch (Exception $e) {
	    	return $e->getMessage(); 
	    }
	}

	/**
     * Uploads and reads a CSV file, sorts, groups and serializes data 
     *
     * @param  file $file contains uploaded file 
     * @return json group of 'BOT' and 'SOLD' arrays 
     * @throws Exception if there is no file or invalid file format
     */	
    public function upload ($file) {

    	try {

    		$fileName = $file->getClientOriginalName();
	    	$path = $file->storeAs($this->rootPath, $fileName, 'public');  			
	   
	    	$originalRecords = (new FastExcel)->import($this->uploadDir . $fileName);

	    	//reformat original CSV columns and data 
			$recordsArray = $this->rearrangeInput($originalRecords);
			$recordsCollection = collect($recordsArray);

			//sort records based on timestamp
			$sortedRecords = collect($recordsCollection->sortBy('timestamp')->values()->all());
			
			//group records by group id
			$group = $sortedRecords->groupBy('group');
			
			//prepare final output for serialization 
			$finalOutput = $this->serializeData($group);			

			return json_encode($finalOutput);

	    } catch (Exception $e) {
	    	return $e->getMessage(); 
	    }
    }
}
