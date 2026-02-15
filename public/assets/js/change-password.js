/**
// change password
 */

'use strict';

$(function () {
  $('#addNewUserForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission
    var formData = new FormData(this);
    $.ajax({
      url: '/change-password',
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
        }
      },
      cache: false,
      contentType: false,
      processData: false,
      error: function (xhr) {
        // Handle error response

        console.log(xhr.responseJSON);
        var errors = xhr.responseJSON.errors;
        if (errors) {
          // Show validation errors
          if (errors.current_password) {
            $('#current_password').addClass('is-invalid');

            $('#current_password')
              .closest('.mb-3')
              .find('.invalid-feedback')
              .html('<div data-field="current_password" data-validator="notEmpty">' + errors.username[0] + '</div>');
          } else {
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
          }
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
  const passwordForm = document.getElementById('addNewUserForm');

  FormValidation.formValidation(passwordForm, {
    fields: {
      current_password: {
        validators: {
          notEmpty: {
            message: 'Current password is required'
          }
        }
      },
      password: {
        validators: {
          notEmpty: {
            message: 'New password is required'
          },
          stringLength: {
            min: 6,
            message: 'The password must be at least 6 characters long'
          }
        }
      },
      confirm_password: {
        validators: {
          notEmpty: {
            message: 'Confirm password is required'
          },
          identical: {
            compare: function () {
              return passwordForm.querySelector('[name="password"]').value;
            },
            message: 'The password and its confirm are not the same'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        eleInvalidClass: '',
        eleValidClass: ''
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      defaultSubmit: new FormValidation.plugins.DefaultSubmit()
    }
  });
});
