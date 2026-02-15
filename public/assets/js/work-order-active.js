/**
 * Page User List
 */

'use strict';

$(function () {
  $('#submit-work-order').on('click', function (e) {
    var id = $('#work-order-id').val();
    var signature = $('#signature').val();
    if (!signature) {
      $('#signature').addClass('is-invalid');
      $('#signature').focus();

      // Show SweetAlert error message
      Swal.fire({
        title: 'Error!',
        text: 'Please sign the work order.',
        icon: 'error',
        customClass: {
          confirmButton: 'btn btn-primary waves-effect waves-light'
        },
        buttonsStyling: false
      });

      return;
    }

    $.ajax({
      url: '/manage-work-orders/sign',
      type: 'POST',
      data: {
        signature: signature,
        id: id,
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {
        if (response.status) {
          // Show SweetAlert success message
          toastr.success(response.message, 'Success', {
            closeButton: true,
            progressBar: true,
            timeOut: 2000,
            positionClass: 'toast-top-right'
          });

          // after 2 seconds redirect to manage-work-orders page
          setTimeout(function () {
            window.location.href = '/manage-work-orders';
          }, 500);
        }
      },
      error: function (xhr, status, error) {
        // Handle error response
        console.log(xhr.responseJSON);
        var errors = xhr.responseJSON.errors;
        if (errors) {
          // Show validation errors
          Swal.fire({
            title: 'Error!',
            text: errors[Object.keys(errors)[0]][0],
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-primary waves-effect waves-light'
            },
            buttonsStyling: false
          });
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
      }
    });
  });

  // const canvas = document.getElementById('signature-pad');

  function clearPad() {
    signaturePad.clear();
  }
});
