'use strict';

$(function () {
  $('#addNewUserForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission
    var formData = new FormData(this);
    $.ajax({
      url: '/manage-techlinks',
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
            window.location.href = '/manage-users';
          }, 2000);
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
          if (errors.username) {
            $('#username').addClass('is-invalid');

            // <div data-field="name" data-validator="notEmpty">Please enter Company name </div>  add this div in html
            $('#username')
              .closest('.mb-3')
              .find('.invalid-feedback')
              .html('<div data-field="username" data-validator="notEmpty">' + errors.username[0] + '</div>');
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

  //delete file
  $(document).on('click', '.delete-file', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var url = '/manage-techlinks/delete-file/' + id;
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'No, cancel!',
      customClass: {
        confirmButton: 'btn btn-primary waves-effect waves-light',
        cancelButton: 'btn btn-secondary waves-effect'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.isConfirmed) {
        $.ajax({
          url: url,
          type: 'get',
          success: function (response) {
            if (response.status) {
              // Show SweetAlert success message
              toastr.success(response.message, 'Success', {
                closeButton: true,
                progressBar: true,
                timeOut: 2000,
                positionClass: 'toast-top-right'
              });
              $('.file-dev').addClass('d-none');
            }
          },
          error: function (xhr, status, error) {
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
        });
      }
    });
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization

  const addNewUserForm = document.getElementById('addNewUserForm');

  // Add New User Form Validation
  const fv = FormValidation.formValidation(addNewUserForm, {
    fields: {
      description: {
        validators: {
          notEmpty: {
            message: 'Please enter description '
          }
        }
      },
      link: {
        validators: {
          notEmpty: {
            message: 'Please enter link'
          }
        }
      },
      file: {
        validators: {
          notEmpty: {
            message: 'Please select file'
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
