<?php
namespace App\Imports;

use App\Models\Checklist;
use App\Models\Task;
use App\Models\Device;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;

class ChecklistImport implements ToModel, WithHeadingRow
{
  public $totalRows = 0;
  public $successfullyImported = 0;
  public $failedRows = [];
  public function model(array $row)
  {
    $this->totalRows++;
    // Create the checklist first
    $validator = Validator::make(
      $row,
      [
        'title' => 'required',
      ],
      [
        'title.required' => 'Checklist title is required.',
      ]
    );
    if ($validator->fails()) {
      $this->failedRows[] = [
        'row' => $row,
        'errors' => $validator->errors()->all(),
      ];
      return null; // skip row
    }

    $checklist = Checklist::Create([
      'title' => $row['title'],
    ]);
    $this->successfullyImported++;

    // Loop through possible 50 task sets
    for ($i = 1; $i <= 50; $i++) {
      $titleKey = "task_{$i}";
      if (!empty($row[$titleKey])) {
        $checklist->tasks()->create([
          'title' => $row[$titleKey],
          'description' => '',
        ]);
      }
    }
  }
}
