/**
 * Page User List
 */

'use strict';

$(function () {
  $('#addNewCustomerForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission
    var formData = new FormData(this);
    $.ajax({
      url: '/manage-customers/store',
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
            window.location.href = '/manage-customers';
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
          if (errors.email) {
            $('#email').addClass('is-invalid');

            // <div data-field="name" data-validator="notEmpty">Please enter Company name </div>  add this div in html
            $('#email')
              .closest('.mb-3')
              .find('.invalid-feedback')
              .html('<div data-field="email" data-validator="notEmpty">' + errors.username[0] + '</div>');
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
  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization

  const phoneMaskList = document.querySelectorAll('.phone-mask'),
    addNewUserForm = document.getElementById('addNewCustomerForm');

  // Add New User Form Validation
  const fv = FormValidation.formValidation(addNewUserForm, {
    fields: {
      company: {
        validators: {
          notEmpty: {
            message: 'Please enter company name'
          }
        }
      },
      primary_contact: {
        validators: {
          notEmpty: {
            message: 'Please enter primary contact'
          }
        }
      },
      primary_email: {
        validators: {
          notEmpty: {
            message: 'Please enter primary email'
          }
        }
      },
      primary_phone: {
        validators: {
          notEmpty: {
            message: 'Please enter primary phone'
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
    $('#addNewCustomerForm').submit();
  });
});

(function () {
  var addEmails = $('.add_emails_left').length;
  $('.addCF').on('click', function () {
    addEmails++;
    console.log(addEmails);
    var newEmail =
      '<tr><td><input type="email" class="form-control add_emails_left" name="additional_emails[]" placeholder="Additional Email" /><a href="javascript:void(0);" class="remCF btn btn-xs btn-danger">Remove</a></td></tr>';
    $('#customEmails').append(newEmail);
  });
  $('#customEmails').on('click', '.remCF', function (e) {
    e.preventDefault();
    if (addEmails > 1) {
      $(this).closest('tr').remove();
      addEmails--;
    }
  });

  $('#change_password').on('change', function () {
    if ($(this).is(':checked')) {
      $('#password_div').show();
      $(this).val(1);
    } else {
      $('#password_div').hide();
      $(this).val(0);
    }
  });
})();
