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

    private function performInserts($rows) {
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
                $newModel->load('customAttrs');
            }
            
            $results[] = $newModel->toArray();
        }

        return $results;
    }

    private function parseCsv($csvFile) {
        $fileContents = $csvFile->getContent();

        $csvReader = Reader::createFromString($fileContents);
        $csvReader->setHeaderOffset(0);
        $headers = $csvReader->getHeader(); //returns the CSV header record
        $recordsIter = $csvReader->getRecords($headers);

        return [
            $headers,
            iterator_to_array($recordsIter, true)
        ];
    }
}