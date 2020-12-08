<?php

namespace App\Services;

use League\Csv\Reader;
use App\Models\Contact;

class ImportMapperService {

    const DEFAULT_ATTRS = [
        'team_id' => '', 'name' => '', 'phone' => '', 'email' => '', 'sticky_phone_number_id' => ''
    ];

    public function importContactsFromCsv($csvFile) {
        list($headers, $rows) = $this->parseCsv($csvFile);

        return $this->performInserts($rows);
    }

    public function performInserts($rows) {
        $results = [];

        foreach($rows as $row) {
            $rowCollect = collect($row);
            $newModel = Contact::create($row);
            $customAttrs = $rowCollect->diffKeys(self::DEFAULT_ATTRS)->all();

            if (count($customAttrs) > 0) {
                foreach($customAttrs as $key => $value) {
                    $newModel->customAttrs()->create(['key' => $key, 'value' => $value]);
                }
                $newModel->fresh();
            }
            
            $results[] = $newModel->load('customAttrs')->toArray();
        }

        return $results;
    }

    private function parseCsv($csvFile) {
        $fileContents = file_get_contents($csvFile->getRealPath());

        $csv = Reader::createFromString($fileContents);
        $csv->setHeaderOffset(0);
        $headers = $csv->getHeader(); //returns the CSV header record
        $recordsIter = $csv->getRecords($headers);

        return [
            $headers,
            iterator_to_array($recordsIter, true)
        ];
    }
}