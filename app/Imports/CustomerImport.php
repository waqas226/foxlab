<?php
namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Throwable;
use Illuminate\Support\Facades\Validator;

class CustomerImport implements ToModel, WithHeadingRow
{
  public $totalRows = 0;
  public $successfullyImported = 0;
  public $failedRows = [];
  public function model(array $row)
  {
    $this->totalRows++;

    $validator = Validator::make(
      $row,
      [
        'company' => 'required|string|max:255|unique:customers,company',
        'primary_contact' => 'required',
        'primary' => 'required',
        'primary_email' => 'required',
      ],
      [
        'company.required' => 'Company name is required.',
        'company.unique' => 'Company name must be unique.',
        'primary_contact.required' => 'Primary contact is required.',
        'primary.required' => 'Primary phone is required.',
        'primary_email.required' => 'Primary email is required.',
      ]
    );

    if ($validator->fails()) {
      $this->failedRows[] = [
        'row' => $row,
        'errors' => $validator->errors()->all(),
      ];
      return null; // skip row
    }
    try {
      $this->successfullyImported++;
      return new Customer([
        'company' => $row['company'],
        'primary_contact' => $row['primary_contact'],
        'primary_phone' => $row['primary'],
        'primary_email' => $row['primary_email'],
        'secondary_contact' => $row['secondary_contact'] ?? '',
        'secondary_phone' => $row['secondary'] ?? '',
        'secondary_email' => $row['secondary_email'] ?? '',
        'address' => $row['address'] ?? '',
        'pm_type' => $row['pm_type'] ?? '',
        'status' => $row['status'] == 'active' ? 'A' : 'D',
        'comment' => $row['comment'] ?? '',
      ]);
    } catch (Throwable $e) {
      // This will be handled by SkipsErrors trait
    }
  }
}
