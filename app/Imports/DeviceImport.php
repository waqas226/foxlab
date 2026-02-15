<?php

namespace App\Imports;

use App\Models\Device;
use App\Models\Customer;
use App\Models\Checklist;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Carbon\Carbon;
use Throwable;
use Illuminate\Support\Facades\Validator;

class DeviceImport implements ToModel, WithHeadingRow
{
  public $totalRows = 0;
  public $successfullyImported = 0;
  public $failedRows = [];

  public function model(array $row)
  {
    $this->totalRows++;
    $customer = Customer::where('company', $row['company'])->first();
    if (!$customer) {
      $this->failedRows[] = [
        'row' => $row,
        'errors' => ['Company not found.'],
      ];
      return null; // skip row
    }
    $validator = Validator::make(
      $row,
      [
        'device_type' => 'required',
        'make' => 'required',
        'model' => 'required',
        'sn' => 'required|unique:devices,sn',
      ],
      [
        'device_type.required' => 'Device type is required.',
        'make.required' => 'Make is required.',
        'model.required' => 'Model is required.',

        'sn.required' => 'Serial number is required.',
        'sn.unique' => 'Serial number must be unique.',
      ]
    );

    if ($validator->fails()) {
      $this->failedRows[] = [
        'row' => $row,
        'errors' => $validator->errors()->all(),
      ];
      return null; // skip row
    }
    // Check if last_pm and next_pm are valid dates
    if (!isset($row['last_pm']) || !isset($row['next_pm'])) {
      $this->failedRows[] = [
        'row' => $row,
        'errors' => ['last_pm and next_pm are required.'],
      ];
      return null; // skip row
    } elseif (!$this->isValidDate($row['last_pm']) || !$this->isValidDate($row['next_pm'])) {
      $this->failedRows[] = [
        'row' => $row,
        'errors' => ['Invalid date format for last_pm or next_pm. Use d/m/Y or Y-m-d format.'],
      ];
      return null; // skip row
    }
    if ($row['checklist'] != '') {
      $checklist = Checklist::where('title', $row['checklist'])->first();
      if ($checklist) {
        $checklistid = $checklist->id;
      }
    } else {
      $checklistid = '';
    }
    $this->successfullyImported++;
    return new Device([
      'device_type' => $row['device_type'],
      'make' => $row['make'],
      'model' => $row['model'],
      'sn' => $row['sn'],
      'last_pm' => $this->isValidDate($row['last_pm']),
      'next_pm' => $this->isValidDate($row['next_pm']),

      'asset' => $row['asset'] ?? null,
      'company_id' => $customer->id,
      'checklist_id' => $checklistid,
    ]);
  }

  function isValidDate($date)
  {
    $formats = ['d/m/Y', 'Y-m-d'];

    foreach ($formats as $format) {
      try {
        $parsed = Carbon::createFromFormat($format, $date);
        if ($parsed && $parsed->format($format) === $date) {
          return $parsed->format('Y-m-d'); // Standardize for DB
        }
      } catch (\Exception $e) {
        continue;
      }
    }

    return null; // Invalid date
  }
}
