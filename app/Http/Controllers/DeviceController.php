<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Company;
use App\Models\SiteConstant;
use App\Imports\DeviceImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Customer;
use App\Models\Checklist;

class DeviceController extends Controller
{
  public function index()
  {
    $customers = Customer::orderBy('company', 'asc')->get();
    $siteconstants = SiteConstant::first();

    return view('manage-device', compact('customers', 'siteconstants'));
  }
  public function create($id = null)
  {
    $customers = Customer::orderBy('company', 'asc')->get();
    $checklists = Checklist::orderBy('title', 'asc')->get();
    if ($id) {
      $device = Device::find($id);
      return view('add-device', compact('id', 'device', 'customers', 'checklists'));
    }
    return view('add-device', compact('customers', 'checklists'));
  }
  public function show(Request $request)
  {
    //with company name instead of company details
    // $data = Device::with('company')->get();
    $devices = Device::with('customer')->get();
    $data = $devices->map(function ($device) {
      $device->company = $device->customer->company ?? ' ';
      $checklist = Checklist::find($device->checklist_id);
      if ($checklist) {
        $device->checklist = $checklist->title;
      } else {
        $device->checklist = '';
      }

      return $device;
      // return [
      //   'id' => $device->id,
      //   'device_type' => $device->device_type,
      //   'make' => $device->make,
      //   'model' => $device->model,
      //   'sn' => $device->sn,
      //   'last_pm' => $device->last_pm ? $device->last_pm->format('Y-m-d') : null,
      //   'next_pm' => $device->next_pm ? $device->next_pm->format('Y-m-d') : null,
      //   'company' =>
      // ];
    });

    return response()->json([
      'data' => $data,
    ]);
    $columns = ['id', 'id', 'next_visit_todo', 'company_id', 'toodo_date'];
    $companies = Company::all();
    $query = Todo::with('user', 'company');
    $totalData = $query->count();
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    $search = $request->input('search.value');

    $query = Todo::with('user', 'company');

    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('next_visit_todo', 'like', "%{$search}%")->orWhereHas('company', function ($q) use ($search) {
          $q->where('name', 'like', "%{$search}%");
        });
      });
    }

    if ($request->input('company_id') == 'all') {
    } elseif ($request->input('company_id')) {
      $query->where('company_id', $request->input('company_id'));
    } else {
      return response()->json([
        'data' => [],
        'draw' => $request->input('draw'),
        'recordsTotal' => $totalData,
        'recordsFiltered' => 0,
      ]);
    }

    $tasks = $query->orderBy($order, $dir);
    return response()->json([
      'data' => $tasks->get(),
      'draw' => $request->input('draw'),
      'recordsTotal' => $totalData,
      'recordsFiltered' => $tasks->count(),
    ]);
  }
  public function store(Request $request)
  {
    $request->validate([
      'device_type' => 'required|string|max:255',
      'make' => 'required|string|max:255',
      'model' => 'required',
      'sn' => 'required|unique:devices,sn,' . $request->id,
      'company_id' => 'required|exists:companies,id',
      'checklist_id' => 'nullable|exists:checklists,id',
      'sn_pic' => 'nullable|string|max:255',
      'asset_pic' => 'nullable|string|max:255',
    ]);

    $data = $request->except(['delete_sn_pic', 'delete_asset_pic', '_token']);

    // Get existing device if updating
    $existingDevice = null;
    if ($request->id) {
      $existingDevice = Device::find($request->id);
    }

    // Handle SN Pic deletion or path
    if ($request->delete_sn_pic == '1') {
      // Delete existing file if exists
      if ($existingDevice && $existingDevice->sn_pic && file_exists(public_path('uploads/' . $existingDevice->sn_pic))) {
        unlink(public_path('uploads/' . $existingDevice->sn_pic));
      }
      $data['sn_pic'] = null;
    } elseif ($request->sn_pic) {
      // Delete old file if exists when uploading new one
      if ($existingDevice && $existingDevice->sn_pic && $existingDevice->sn_pic != $request->sn_pic && file_exists(public_path('uploads/' . $existingDevice->sn_pic))) {
        unlink(public_path('uploads/' . $existingDevice->sn_pic));
      }
      $data['sn_pic'] = $request->sn_pic;
    }

    // Handle Asset Pic deletion or path
    if ($request->delete_asset_pic == '1') {
      // Delete existing file if exists
      if ($existingDevice && $existingDevice->asset_pic && file_exists(public_path('uploads/' . $existingDevice->asset_pic))) {
        unlink(public_path('uploads/' . $existingDevice->asset_pic));
      }
      $data['asset_pic'] = null;
    } elseif ($request->asset_pic) {
      // Delete old file if exists when uploading new one
      if ($existingDevice && $existingDevice->asset_pic && $existingDevice->asset_pic != $request->asset_pic && file_exists(public_path('uploads/' . $existingDevice->asset_pic))) {
        unlink(public_path('uploads/' . $existingDevice->asset_pic));
      }
      $data['asset_pic'] = $request->asset_pic;
    }

    if ($request->id) {
      $device = Device::findOrFail($request->id);
      $device->update($data);
      $msg = 'Updated Successfully';
    } else {
      $device = Device::create($data);
      $msg = 'Added Successfully';
    }

    return response()->json(['status' => true, 'message' => $msg]);
  }
  public function import(Request $request)
  {
    $request->validate([
      'file' => 'required|file|mimes:xlsx,csv',
    ]);

    $import = new DeviceImport();
    Excel::import($import, $request->file('file'));

    return back()->with([
      'success' => "{$import->successfullyImported} of {$import->totalRows} device rows imported.",
      'failed' => $import->failedRows,
    ]);
  }
  public function destroy($id)
  {
    $device = Device::findOrFail($id);
    $device->delete();

    return response()->json([
      'status' => true,
      'message' => 'Device deleted successfully.',
    ]);
  }

  public function deletePicture(Request $request)
  {
    $request->validate([
      'device_id' => 'required|exists:devices,id',
      'picture_type' => 'required|in:sn_pic,asset_pic',
    ]);

    $device = Device::findOrFail($request->device_id);
    $pictureType = $request->picture_type;

    // Delete the file from server
    if ($device->$pictureType && file_exists(public_path('uploads/' . $device->$pictureType))) {
      unlink(public_path('uploads/' . $device->$pictureType));
    }

    // Update database to set picture field to null
    $device->$pictureType = null;
    $device->save();

    return response()->json([
      'status' => true,
      'message' => 'Picture deleted successfully.',
    ]);
  }

  public function uploadImage(Request $request)
  {
    try {
      // Validate type only - completely skip file validation
      $request->validate([
        'type' => 'nullable|in:sn_pic,asset_pic',
      ]);

      // Check if file exists - use multiple methods
      $image = null;
      if ($request->hasFile('image')) {
        $image = $request->file('image');
      } elseif ($request->file('image')) {
        $image = $request->file('image');
      }
      
      if (!$image) {
        \Log::error('No file in request:', [
          'all_files' => $request->allFiles(),
          'has_image' => $request->hasFile('image'),
          'input_keys' => array_keys($request->all()),
          'files_array' => $_FILES ?? 'no files',
          'content_type' => $request->header('Content-Type'),
          'content_length' => $request->header('Content-Length')
        ]);
        return response()->json([
          'status' => false,
          'message' => 'No image file received. Please check: 1) File size is under 2MB, 2) File is a valid image format, 3) Browser supports file uploads.'
        ], 400);
      }
      
      $type = $request->input('type', 'image');
      
      // Check if file is valid
      if (!$image->isValid()) {
        $errorCode = $image->getError();
        $errorMessages = [
          UPLOAD_ERR_INI_SIZE => 'File exceeds PHP upload_max_filesize setting',
          UPLOAD_ERR_FORM_SIZE => 'File exceeds form MAX_FILE_SIZE',
          UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
          UPLOAD_ERR_NO_FILE => 'No file was uploaded',
          UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
          UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
          UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
        ];
        
        // Get PHP limits for better error message
        $uploadMaxFilesize = ini_get('upload_max_filesize');
        $postMaxSize = ini_get('post_max_size');
        $maxFileSize = min(
          $this->parseSize($uploadMaxFilesize),
          $this->parseSize($postMaxSize),
          2 * 1024 * 1024 // 2MB application limit
        );
        
        \Log::error('Invalid file upload:', [
          'hasFile' => $request->hasFile('image'),
          'isValid' => $image->isValid(),
          'errorCode' => $errorCode,
          'errorMessage' => $errorMessages[$errorCode] ?? 'Unknown error',
          'uploadMaxFilesize' => $uploadMaxFilesize,
          'postMaxSize' => $postMaxSize
        ]);
        
        $errorMsg = $errorMessages[$errorCode] ?? 'Error code ' . $errorCode;
        if ($errorCode == UPLOAD_ERR_INI_SIZE || $errorCode == UPLOAD_ERR_FORM_SIZE) {
          $errorMsg .= '. Maximum file size allowed: ' . $this->formatBytes($maxFileSize) . '. Please compress your image and try again.';
        }
        
        return response()->json([
          'status' => false,
          'message' => 'File upload failed: ' . $errorMsg
        ], 400);
      }
      
      // Check file size manually (2MB = 2048 KB)
      $maxSize = 2048 * 1024; // 2MB
      if ($image->getSize() > $maxSize) {
        return response()->json([
          'status' => false,
          'message' => 'File size (' . round($image->getSize() / 1024 / 1024, 2) . 'MB) exceeds 2MB limit. Please compress the image and try again.'
        ], 400);
      }
      
      // Get PHP upload limits for better error messages
      $uploadMaxFilesize = ini_get('upload_max_filesize');
      $postMaxSize = ini_get('post_max_size');
      
      // Log file info for debugging
      \Log::info('Upload attempt:', [
        'original_name' => $image->getClientOriginalName(),
        'mime_type' => $image->getMimeType(),
        'extension' => $image->getClientOriginalExtension(),
        'guessed_extension' => $image->guessExtension(),
        'size' => $image->getSize()
      ]);
      
      // Check if it's actually an image by MIME type or content
      $mimeType = $image->getMimeType();
      $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
      
      // Also check file content (first bytes) for image signatures
      $isImage = false;
      if (in_array($mimeType, $allowedMimes)) {
        $isImage = true;
      } else {
        // Check file content for image signatures
        $fileHandle = fopen($image->getRealPath(), 'rb');
        $firstBytes = fread($fileHandle, 12);
        fclose($fileHandle);
        
        // JPEG signature: FF D8 FF
        // PNG signature: 89 50 4E 47
        // GIF signature: 47 49 46 38
        if (substr($firstBytes, 0, 3) === "\xFF\xD8\xFF" || 
            substr($firstBytes, 0, 4) === "\x89\x50\x4E\x47" ||
            substr($firstBytes, 0, 4) === "GIF8") {
          $isImage = true;
        }
      }
      
      if (!$isImage && !in_array($mimeType, $allowedMimes)) {
        return response()->json([
          'status' => false,
          'message' => 'Invalid image file. Please upload a valid image (JPEG, PNG, GIF, or WebP).',
          'debug' => [
            'mime_type' => $mimeType,
            'extension' => $image->getClientOriginalExtension()
          ]
        ], 422);
      }
      
      // Get file extension - prefer guessed extension for converted files
      $extension = $image->guessExtension();
      if (empty($extension) || !in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        // Fallback to original extension
        $extension = $image->getClientOriginalExtension();
        if (empty($extension) || !in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
          // Default to jpg for converted HEIC files
          $extension = 'jpg';
        }
      }
      
      // Normalize extension
      if (in_array(strtolower($extension), ['jpg', 'jpeg'])) {
        $extension = 'jpg';
      }
      
      // Generate unique filename
      $filename = time() . '_' . $type . '_' . uniqid() . '.' . $extension;
      
      // Move file to uploads directory
      $image->move(public_path('uploads'), $filename);
      
      \Log::info('Image uploaded successfully:', ['filename' => $filename]);
      
      return response()->json([
        'status' => true,
        'message' => 'Image uploaded successfully',
        'path' => $filename,
        'url' => asset('uploads/' . $filename)
      ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
      \Log::error('Validation error:', $e->errors());
      return response()->json([
        'status' => false,
        'message' => 'Validation failed',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('Image upload error: ' . $e->getMessage(), [
        'trace' => $e->getTraceAsString()
      ]);
      return response()->json([
        'status' => false,
        'message' => 'Failed to upload image: ' . $e->getMessage()
      ], 500);
    }
  }
  
  /**
   * Parse PHP size string (e.g., "2M", "512K") to bytes
   */
  private function parseSize($size)
  {
    $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
    $size = preg_replace('/[^0-9\.]/', '', $size);
    if ($unit) {
      return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
    }
    return round($size);
  }
  
  /**
   * Format bytes to human readable format
   */
  private function formatBytes($bytes, $precision = 2)
  {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
  }
}
