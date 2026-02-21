/**
 * Page User List
 */

'use strict';

$(function () {
  // Initialize Select2 for make and model dropdowns
  $('#make').on('change', function () {
    var makeId = $(this).val();
    var checkId = $('#id').val();
    if (makeId) {
      var urlData = makeId;
      if (checkId) {
        urlData = makeId + '/' + checkId;
      }
      $.ajax({
        url: '/manage-checklists/get-models/' + urlData,
        type: 'GET',
        success: function (response) {
          if (response.status) {
            var modelSelect = $('#model');
            modelSelect.empty();
            modelSelect.append('<option value="">Select Model</option>');
            $.each(response.data, function (index, model) {
              modelSelect.append('<option value="' + model.id + '">' + model.model + '</option>');
            });
          } else {
            toastr.error(response.message, 'Error', {
              closeButton: true,
              progressBar: true,
              timeOut: 3000,
              positionClass: 'toast-top-right'
            });
          }
        },
        error: function (xhr) {
          console.log(xhr.responseJSON);
          toastr.error('Failed to load models. Please try again.', 'Error', {
            closeButton: true,
            progressBar: true,
            timeOut: 3000,
            positionClass: 'toast-top-right'
          });
          //if error 419 or 401, redirect to login page
          if (xhr.status === 419 || xhr.status === 401) {
            window.location.href = '/login';
          }
        }
      });
    } else {
      $('#model').empty().append('<option value="">Select Model</option>');
    }
  });
  $('#addNewUserForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission
    var formData = new FormData(this);
    $.ajax({
      url: '/manage-checklists/store',
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
            window.location.href = '/manage-checklists';
          }, 2000);
        } else {
          Swal.fire({
            title: 'Error!',
            text: response.message,
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-primary waves-effect waves-light'
            },
            buttonsStyling: false
          });
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

        if (xhr.status === 419 || xhr.status === 401) {
          window.location.href = '/login';
        }
      }
    });
  });
  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization

  const phoneMaskList = document.querySelectorAll('.phone-mask'),
    addNewUserForm = document.getElementById('addNewUserForm');

  // Add New User Form Validation
  const fv = FormValidation.formValidation(addNewUserForm, {
    fields: {
      title: {
        validators: {
          notEmpty: {
            message: 'Please enter title '
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
    $('#addNewUserForm').submit();
  });
});

(function () {
  var addEmails = $('.add_task_left').length;
  $('.addCF').on('click', function () {
    if (addEmails > 49) {
      Swal.fire({
        title: 'Error!',
        text: 'You can add a maximum of 50 tasks.',
        icon: 'error',
        customClass: {
          confirmButton: 'btn btn-primary waves-effect waves-light'
        },
        buttonsStyling: false
      });
      return;
    }
    addEmails++;
    var newEmail =
      '<tr class="add_task_left"><td class="taskid">' +
      addEmails +
      '</td>' +
      '<td><div class="mb-3"><input type="text" class="form-control"  placeholder="Task Title" name="task_title[]" required  /></div>' +
      '<a href="javascript:void(0);" class="remCF btn btn-xs btn-danger">Remove</a></td> </tr>';
    $('#customEmails').append(newEmail);
  });
  $('#customEmails').on('click', '.remCF', function (e) {
    e.preventDefault();
    if (addEmails > 1) {
      $(this).closest('tr').remove();
      addEmails--;
    }

    // Update task IDs
    $('#customEmails tr.add_task_left').each(function (index) {
      $(this).find('.taskid').text(index + 1);
    });
  });
})();
