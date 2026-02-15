'use strict';

$(function () {
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
  const fullEditor = new Quill('#email_template', {
    bounds: '#email_template',
    placeholder: 'Type Something...',
    modules: {
      formula: true,
      toolbar: fullToolbar
    },
    theme: 'snow'
  });

  $('#siteConstantForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission
    var formData = new FormData(this);
    formData.append('email_template', fullEditor.root.innerHTML);

    $.ajax({
      url: '/manage-site-constants',
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
          if (errors.email_template) {
            $('#email_template').addClass('is-invalid');
            $('#email_template')
              .closest('.mb-3')
              .find('.invalid-feedback')
              .html(
                '<div data-field="email_template" data-validator="notEmpty">' + errors.email_template[0] + '</div>'
              );
          } else {
            // Show SweetAlert error message
            toastr.error(xhr.responseJSON.message, 'Error', {
              closeButton: true,
              progressBar: true,
              timeOut: 3000,
              positionClass: 'toast-top-right'
            });
          }
        } else {
          // Show generic error message

          // 'Something went wrong. Please try again.'
          toastr.error('Something went wrong. Please try again.', 'Error', {
            closeButton: true,
            progressBar: true,
            timeOut: 3000,
            positionClass: 'toast-top-right'
          });
        }
      }
    });
  });
});
