/**
 * Page User List
 */

'use strict';

$(function () {
  const flatPickrList = [].slice.call(document.querySelectorAll('.flatpickr-validation'));
  // Flat pickr
  if (flatPickrList) {
    flatPickrList.forEach(flatPickr => {
      flatPickr.flatpickr({
        monthSelectorType: 'static'
      });
    });
  }
  // Function to compress image
  async function compressImage(file, quality = 0.8) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
          const canvas = document.createElement('canvas');
          let width = img.width;
          let height = img.height;
          
          // Calculate new dimensions if image is very large
          const maxDimension = 2048; // Max width or height
          if (width > maxDimension || height > maxDimension) {
            if (width > height) {
              height = (height / width) * maxDimension;
              width = maxDimension;
            } else {
              width = (width / height) * maxDimension;
              height = maxDimension;
            }
          }
          
          canvas.width = width;
          canvas.height = height;
          
          const ctx = canvas.getContext('2d');
          ctx.drawImage(img, 0, 0, width, height);
          
          canvas.toBlob(function(blob) {
            if (!blob) {
              reject(new Error('Compression failed'));
              return;
            }
            
            const compressedFile = new File([blob], file.name, {
              type: 'image/jpeg',
              lastModified: Date.now()
            });
            
            console.log('Image compressed:', {
              originalSize: file.size,
              compressedSize: compressedFile.size,
              reduction: ((1 - compressedFile.size / file.size) * 100).toFixed(1) + '%'
            });
            
            resolve(compressedFile);
          }, 'image/jpeg', quality);
        };
        img.onerror = reject;
        img.src = e.target.result;
      };
      reader.onerror = reject;
      reader.readAsDataURL(file);
    });
  }

  // Function to convert HEIC to JPEG
  async function convertHeicToJpeg(file) {
    if (file && (file.name.toLowerCase().endsWith('.heic') || file.name.toLowerCase().endsWith('.heif'))) {
      // Check if heic2any library is loaded
      if (typeof heic2any === 'undefined') {
        toastr.error('HEIC conversion library not loaded. Please refresh the page and try again.', 'Library Error', {
          closeButton: true,
          progressBar: true,
          timeOut: 5000
        });
        throw new Error('heic2any library not available');
      }
      
      try {
        const blob = await heic2any({
          blob: file,
          toType: 'image/jpeg',
          quality: 0.92
        });
        
        // heic2any returns an array, get the first blob
        let convertedBlob = Array.isArray(blob) ? blob[0] : blob;
        
        // Ensure it's a Blob
        if (!(convertedBlob instanceof Blob)) {
          console.error('Conversion did not return a Blob:', convertedBlob);
          throw new Error('HEIC conversion failed - invalid blob returned');
        }
        
        // Create a new File object with the converted blob
        // Ensure proper filename with .jpg extension
        const baseName = file.name.replace(/\.(heic|heif)$/i, '');
        const newFileName = baseName + '.jpg';
        
        // Create file with explicit JPEG MIME type
        const convertedFile = new File([convertedBlob], newFileName, {
          type: 'image/jpeg',
          lastModified: Date.now()
        });
        
        // Verify the file was created correctly
        if (!(convertedFile instanceof File)) {
          console.error('Failed to create File object from blob');
          throw new Error('Failed to create file from converted HEIC');
        }
        
        console.log('HEIC converted to JPEG:', {
          originalName: file.name,
          newName: convertedFile.name,
          type: convertedFile.type,
          size: convertedFile.size,
          blobType: convertedBlob.type,
          isFile: convertedFile instanceof File,
          isBlob: convertedFile instanceof Blob
        });
        
        return convertedFile;
      } catch (error) {
        console.error('HEIC conversion error:', error);
        throw error;
      }
    }
    return file;
  }

  // Function to upload image instantly
  async function uploadImageInstantly(file, type) {
    const inputId = type === 'sn_pic' ? 'sn_pic' : 'asset_pic';
    const pathInputId = type === 'sn_pic' ? 'sn_pic_path' : 'asset_pic_path';
    const statusDivId = type === 'sn_pic' ? 'sn_pic_upload_status' : 'asset_pic_upload_status';
    const statusTextId = type === 'sn_pic' ? 'sn_pic_status_text' : 'asset_pic_status_text';
    const previewDivId = type === 'sn_pic' ? 'sn_pic_preview' : 'asset_pic_preview';
    const previewImgId = type === 'sn_pic' ? 'sn_pic_preview_img' : 'asset_pic_preview_img';
    const progressBar = document.querySelector(`#${statusDivId} .progress-bar`);
    
    // Show upload status
    document.getElementById(statusDivId).style.display = 'block';
    document.getElementById(statusTextId).textContent = 'Converting and uploading...';
    progressBar.style.width = '30%';
    
    try {
      // Convert HEIC if needed
      let fileToUpload = file;
      if (file.name.toLowerCase().endsWith('.heic') || file.name.toLowerCase().endsWith('.heif')) {
        document.getElementById(statusTextId).textContent = 'Converting HEIC to JPEG...';
        progressBar.style.width = '50%';
        fileToUpload = await convertHeicToJpeg(file);
      }
      
      // Ensure file has proper extension and MIME type
      let uploadFile = fileToUpload;
      
      // Always ensure .jpg extension for converted HEIC files
      if (file.name.toLowerCase().endsWith('.heic') || file.name.toLowerCase().endsWith('.heif')) {
        const baseName = uploadFile.name.replace(/\.(jpg|jpeg|png|gif|webp)$/i, '');
        uploadFile = new File([uploadFile], baseName + '.jpg', {
          type: 'image/jpeg',
          lastModified: Date.now()
        });
      } else if (!uploadFile.name.toLowerCase().match(/\.(jpg|jpeg|png|gif|webp)$/)) {
        // If no valid extension, add .jpg
        const baseName = uploadFile.name.replace(/\.[^/.]+$/, '');
        uploadFile = new File([uploadFile], baseName + '.jpg', {
          type: 'image/jpeg',
          lastModified: Date.now()
        });
      }
      
      // Always ensure MIME type is set correctly
      if (!uploadFile.type || !uploadFile.type.startsWith('image/')) {
        // Determine MIME type from extension
        const ext = uploadFile.name.split('.').pop().toLowerCase();
        let mimeType = 'image/jpeg';
        if (ext === 'png') mimeType = 'image/png';
        else if (ext === 'gif') mimeType = 'image/gif';
        else if (ext === 'webp') mimeType = 'image/webp';
        
        uploadFile = new File([uploadFile], uploadFile.name, {
          type: mimeType,
          lastModified: Date.now()
        });
      }
      
      // Compress image if it's too large (over 1.5MB)
      const maxSizeBeforeCompression = 1.5 * 1024 * 1024; // 1.5MB
      if (uploadFile.size > maxSizeBeforeCompression) {
        document.getElementById(statusTextId).textContent = 'Compressing image...';
        progressBar.style.width = '60%';
        uploadFile = await compressImage(uploadFile, 0.7); // 70% quality
      }
      
      // Create FormData for upload
      const formData = new FormData();
      
      // Ensure we're sending a proper File object
      // Create a new File from the blob to ensure it's valid
      const fileToSend = uploadFile instanceof File ? uploadFile : new File([uploadFile], uploadFile.name, {
        type: uploadFile.type || 'image/jpeg',
        lastModified: uploadFile.lastModified || Date.now()
      });
      
      formData.append('image', fileToSend, fileToSend.name);
      formData.append('type', type);
      formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
      
      // Debug: Log file info
      console.log('Uploading file:', {
        name: fileToSend.name,
        type: fileToSend.type,
        size: fileToSend.size,
        lastModified: fileToSend.lastModified,
        isFile: fileToSend instanceof File,
        isBlob: fileToSend instanceof Blob
      });
      
      // Verify FormData contents
      console.log('FormData entries:');
      for (let pair of formData.entries()) {
        console.log(pair[0] + ': ', pair[1]);
      }
      
      // Update progress
      progressBar.style.width = '70%';
      document.getElementById(statusTextId).textContent = 'Uploading...';
      
      // Upload image
      let response;
      try {
        response = await $.ajax({
          url: '/manage-devices/upload-image',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          xhr: function() {
            const xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(e) {
              if (e.lengthComputable) {
                const percentComplete = 70 + (e.loaded / e.total) * 30;
                progressBar.style.width = percentComplete + '%';
              }
            }, false);
            return xhr;
          }
        });
      } catch (ajaxError) {
        // Handle AJAX errors (including validation errors)
        const errorResponse = ajaxError.responseJSON || {};
        throw {
          responseJSON: errorResponse,
          status: ajaxError.status,
          statusText: ajaxError.statusText,
          message: errorResponse.message || ajaxError.statusText || 'Upload failed'
        };
      }
      
      if (response && response.status) {
        // Hide upload status, show preview
        document.getElementById(statusDivId).style.display = 'none';
        document.getElementById(pathInputId).value = response.path;
        document.getElementById(previewImgId).src = response.url;
        document.getElementById(previewDivId).style.display = 'block';
        
        // Clear file input
        document.getElementById(inputId).value = '';
        
        toastr.success('Image uploaded successfully', 'Success', {
          closeButton: true,
          progressBar: true,
          timeOut: 2000
        });
      } else {
        throw new Error(response.message || 'Upload failed');
      }
    } catch (error) {
      document.getElementById(statusDivId).style.display = 'none';
      
      // Better error handling
      let errorMessage = 'Failed to upload image';
      if (error.responseJSON) {
        if (error.responseJSON.errors) {
          const errors = error.responseJSON.errors;
          if (errors.image) {
            errorMessage = Array.isArray(errors.image) ? errors.image[0] : errors.image;
          } else {
            errorMessage = error.responseJSON.message || errorMessage;
          }
        } else {
          errorMessage = error.responseJSON.message || errorMessage;
        }
      } else if (error.message) {
        errorMessage = error.message;
      }
      
      console.error('Upload error details:', {
        error: error,
        responseJSON: error.responseJSON,
        status: error.status,
        statusText: error.statusText
      });
      
      toastr.error(errorMessage, 'Upload Error', {
        closeButton: true,
        progressBar: true,
        timeOut: 5000
      });
      // Clear file input on error
      document.getElementById(inputId).value = '';
      throw error;
    }
  }

  // Handle file input changes for instant upload
  $('#sn_pic').on('change', async function(e) {
    const file = e.target.files[0];
    if (file) {
      await uploadImageInstantly(file, 'sn_pic');
    }
  });

  $('#asset_pic').on('change', async function(e) {
    const file = e.target.files[0];
    if (file) {
      await uploadImageInstantly(file, 'asset_pic');
    }
  });

  // Handle remove buttons
  $('#sn_pic_remove_btn').on('click', function() {
    document.getElementById('sn_pic_path').value = '';
    document.getElementById('sn_pic_preview').style.display = 'none';
    document.getElementById('sn_pic').value = '';
  });

  $('#asset_pic_remove_btn').on('click', function() {
    document.getElementById('asset_pic_path').value = '';
    document.getElementById('asset_pic_preview').style.display = 'none';
    document.getElementById('asset_pic').value = '';
  });

  // Initialize existing images if editing (handled in blade template)

  $('#addNewDeviceForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission
    
    // Create FormData and exclude file inputs (we only send paths)
    var formData = new FormData();
    var form = this;
    
    // Add all form fields except file inputs
    $(form).find('input, select, textarea').each(function() {
      var $field = $(this);
      var name = $field.attr('name');
      var type = $field.attr('type');
      
      // Skip file inputs and buttons
      if (type === 'file' || type === 'button' || type === 'submit') {
        return;
      }
      
      // Add the field to FormData
      if (name) {
        if (type === 'checkbox') {
          if ($field.is(':checked')) {
            formData.append(name, $field.val() || '1');
          }
        } else if (type === 'radio') {
          if ($field.is(':checked')) {
            formData.append(name, $field.val());
          }
        } else {
          formData.append(name, $field.val() || '');
        }
      }
    });
    $.ajax({
      url: '/manage-devices/store',
      type: 'POST',
      data: formData,
      success: function (response) {
        if (response.status) {
          // Show SweetAlert success message
          toastr.success(response.message, 'Success', {
            closeButton: true,
            progressBar: true,
            timeOut: 2000,
            positionClass: 'toast-top-right'
          });

          // after 2 seconds redirect to manage-users page
          setTimeout(function () {
            window.location.href = '/manage-devices';
          }, 500);
        }
      },
      cache: false,
      contentType: false,
      processData: false,
      error: function (xhr, status, error) {
        // Handle error response

        console.log(xhr.responseJSON);
        var errors = xhr.responseJSON.errors;
        if (errors) {
          // Show validation errors

          // Show SweetAlert error message
          Swal.fire({
            title: 'Error!',
            text: xhr.responseJSON.message,
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-primary waves-effect waves-light'
            },
            buttonsStyling: false
          });
        } else {
          // Show generic error message
          Swal.fire({
            title: 'Error!',
            text: 'Something went wrong. Please try again.',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-primary waves-effect waves-light'
            },
            buttonsStyling: false
          });
        }
      }
    });
  });
  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization

  const   addNewUserForm = document.getElementById('addNewCustomerForm');

  // Add New User Form Validation
  const fv = FormValidation.formValidation(addNewUserForm, {
    fields: {
      device_type: {
        validators: {
          notEmpty: {
            message: 'Please enter Device Type '
          }
        }
      },
      make: {
        validators: {
          notEmpty: {
            message: 'Please enter make'
          }
        }
      },
      model: {
        validators: {
          notEmpty: {
            message: 'Please enter model'
          }
        }
      },
      company_id: {
        validators: {
          notEmpty: {
            message: 'Please select your company'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        eleValidClass: '',
        rowSelector: function (field, ele) {
          // field is the field name & ele is the field element
          return '.mb-3';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      // Submit the form when all fields are valid
      // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  }).on('core.form.valid', function () {
    // Submit the form when valid
    $('#addNewDeviceForm').submit();
  });

  // Delete SN Pic button handler (for existing images when editing)
  $(document).on('click', '.delete-sn-pic-btn', function() {
    var deviceId = $('#device_id').val();

    Swal.fire({
      title: 'Are you sure?',
      text: 'This will permanently delete the SN picture from the server.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'btn btn-danger waves-effect waves-light',
        cancelButton: 'btn btn-secondary waves-effect waves-light'
      },
      buttonsStyling: false
    }).then((result) => {
      if (result.isConfirmed) {
        // Make AJAX call to delete immediately
        $.ajax({
          url: '/manage-devices/delete-picture',
          type: 'POST',
          data: {
            device_id: deviceId,
            picture_type: 'sn_pic',
            _token: $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            if (response.status) {
              $('#sn_pic_container').fadeOut(300, function() {
                $(this).remove();
              });
              $('#sn_pic').val('');
              $('#sn_pic_path').val('');
              $('#sn_pic_preview').hide();
              Swal.fire({
                title: 'Deleted!',
                text: 'SN picture has been permanently deleted.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
              });
            }
          },
          error: function (xhr) {
            Swal.fire({
              title: 'Error!',
              text: xhr.responseJSON?.message || 'Failed to delete picture.',
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary waves-effect waves-light'
              },
              buttonsStyling: false
            });
          }
        });
      }
    });
  });

  // Delete Asset Pic button handler (for existing images when editing)
  $(document).on('click', '.delete-asset-pic-btn', function() {
    var deviceId = $('#device_id').val();

    Swal.fire({
      title: 'Are you sure?',
      text: 'This will permanently delete the Asset picture from the server.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'btn btn-danger waves-effect waves-light',
        cancelButton: 'btn btn-secondary waves-effect waves-light'
      },
      buttonsStyling: false
    }).then((result) => {
      if (result.isConfirmed) {
        // Make AJAX call to delete immediately
        $.ajax({
          url: '/manage-devices/delete-picture',
          type: 'POST',
          data: {
            device_id: deviceId,
            picture_type: 'asset_pic',
            _token: $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            if (response.status) {
              $('#asset_pic_container').fadeOut(300, function() {
                $(this).remove();
              });
              $('#asset_pic').val('');
              $('#asset_pic_path').val('');
              $('#asset_pic_preview').hide();
              Swal.fire({
                title: 'Deleted!',
                text: 'Asset picture has been permanently deleted.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
              });
            }
          },
          error: function (xhr) {
            Swal.fire({
              title: 'Error!',
              text: xhr.responseJSON?.message || 'Failed to delete picture.',
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary waves-effect waves-light'
              },
              buttonsStyling: false
            });
          }
        });
      }
    });
  });
});
