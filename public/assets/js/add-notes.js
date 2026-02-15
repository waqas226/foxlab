/**
 * Page User List
 */

'use strict';

$(function () {
  $('#addNotesForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission
    var formData = new FormData(this);
    $.ajax({
      url: '/manage-notes',
      type: 'POST',
      data: formData,
      success: function (response) {
        if (response.status) {
          // Show SweetAlert success message
          //   toastr.success(response.message, 'Success', {
          //     closeButton: true,
          //     progressBar: true,
          //     timeOut: 2000,
          //     positionClass: 'toast-top-right'
          //   });
          // after 2 seconds redirect to manage-users page
          window.location.href = '/manage-notes';
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
            $('#title').addClass('is-invalid');

            // <div data-field="name" data-validator="notEmpty">Please enter Company name </div>  add this div in html
            $('#title')
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
  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  const fullToolbar = [
    [
      {
        font: []
      },
      {
        size: []
      }
    ],
    ['bold', 'italic', 'underline', 'strike'],
    [
      {
        color: []
      },
      {
        background: []
      }
    ],
    [
      {
        script: 'super'
      },
      {
        script: 'sub'
      }
    ],
    [
      {
        header: '1'
      },
      {
        header: '2'
      },
      'blockquote',
      'code-block'
    ],
    [
      {
        list: 'ordered'
      },
      {
        list: 'bullet'
      },
      {
        indent: '-1'
      },
      {
        indent: '+1'
      }
    ],
    [{ direction: 'rtl' }],
    ['link', 'image', 'video', 'formula'],
    ['clean']
  ];
  const fullEditor = new Quill('#full-editor', {
    bounds: '#full-editor',
    placeholder: 'Type Something...',
    modules: {
      formula: true,
      toolbar: fullToolbar
    },
    theme: 'snow'
  });
  fullEditor.on('text-change', function () {
    const editorContent = fullEditor.root.innerHTML;
    $('#items').val(editorContent);
    //_token
    const token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      url: '/manage-notes/save-item',
      type: 'POST',
      data: { long_desc: editorContent, _token: token },
      success: function (response) {
        console.log('Content auto-saved successfully.');
      },
      error: function () {
        console.error('Failed to auto-save content.');
      }
    });
  });
  const addNewUserForm = document.getElementById('addNotesForm');

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
    $('#addNotesForm').submit();
  });
});
