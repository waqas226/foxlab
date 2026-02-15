'use strict';

$(function () {
  $('#update-work-checklist').on('click', function (e) {
    e.preventDefault();
    var work_order_id = $('#work_order_id').val();
    var device_id = $('#device_id').val();

    var tasks = [];
    $('input[name="task_id[]"]').each(function () {
      tasks.push($(this).val());
    });
    var completed = [];
    $('input[name="completed[]"]').each(function () {
      if ($(this).is(':checked')) {
        completed.push(1);
      } else {
        completed.push(0);
      }
    });
    var notes = [];
    $('textarea[name="notes[]"]').each(function () {
      notes.push($(this).val());
    });
    var description = [];
    $('textarea[name="description[]"]').each(function () {
      description.push($(this).val());
    });
    var quantity = [];
    $('input[name="quantity[]"]').each(function () {
      quantity.push($(this).val());
    });
    $.ajax({
      url: '/work-order-checklist',
      type: 'POST',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        tasks: tasks,
        completed: completed,
        notes: notes,
        description: description,
        quantity: quantity,
        work_order_id: work_order_id,
        device_id: device_id
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
            window.location.href = '/manage-work-orders/' + work_order_id;
          }, 500);
        } else {
          Swal.fire({
            title: 'Error!',
            text: response.message,
            icon: 'error'
          });
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
});
