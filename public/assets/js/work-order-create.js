/**
 * Page User List
 */

'use strict';

$(function () {
  // Update sort order numbers when rows are moved
  function updateSortOrders() {
    $('#devices-table tbody tr').each(function(index) {
      var sortOrder = index + 1;
      $(this).attr('data-sort-order', sortOrder);
      $(this).find('.sort-order-input').val(sortOrder);
    });
  }

  // Move row up
  $(document).on('click', '.move-up', function() {
    var row = $(this).closest('tr');
    var prevRow = row.prev();
    if (prevRow.length) {
      row.insertBefore(prevRow);
      updateSortOrders();
    }
  });

  // Move row down
  $(document).on('click', '.move-down', function() {
    var row = $(this).closest('tr');
    var nextRow = row.next();
    if (nextRow.length) {
      row.insertAfter(nextRow);
      updateSortOrders();
    }
  });

  // Handle sort order input change
  $(document).on('change', '.sort-order-input', function() {
    var targetOrder = parseInt($(this).val());
    var row = $(this).closest('tr');
    var tbody = $('#devices-table tbody');
    var totalRows = tbody.find('tr').length;
    
    // Validate input
    if (isNaN(targetOrder) || targetOrder < 1) {
      targetOrder = 1;
      $(this).val(1);
    } else if (targetOrder > totalRows) {
      targetOrder = totalRows;
      $(this).val(totalRows);
    }

    // Remove row from current position
    var currentIndex = tbody.find('tr').index(row);
    
    // Insert at new position (targetOrder - 1 because it's 0-indexed)
    var targetIndex = targetOrder - 1;
    
    if (targetIndex !== currentIndex) {
      var rows = tbody.find('tr').toArray();
      rows.splice(currentIndex, 1);
      rows.splice(targetIndex, 0, row[0]);
      tbody.empty().append(rows);
      updateSortOrders();
    }
  });

  $('#type').on('change', function () {
    if ($(this).val() === 'Repair') {
      $('#notes-div').removeClass('d-none');
    } else {
      $('#notes-div').addClass('d-none');
    }
  });
  $('#create-work-order').on('click', function (e) {
    //get all values from the checkbox checked ,name="checkDevice[]"
    var qb = $('#qb').val();
    var client_po = $('#client_po').val();
    var cid = $('#customer_id').val();
    var type = $('#type').val();
    var notes = $('#notes').val();
    if (!type) {
      $('#type').addClass('is-invalid');
      $('#type').focus();

      return;
    }
    if (!qb) {
      $('#qb').addClass('is-invalid');
      $('#qb').focus();

      return;
    }
    var selectedDevices = [];
    var deviceSortOrders = {};
    
    // Get checked devices and collect them with their current sort order
    var checkedDevices = [];
    $('#devices-table tbody tr').each(function() {
      var checkbox = $(this).find('input[name="checkDevice[]"]');
      if (checkbox.is(':checked')) {
        var deviceId = checkbox.val();
        var sortOrder = parseInt($(this).find('.sort-order-input').val()) || 1;
        checkedDevices.push({
          id: deviceId,
          sortOrder: sortOrder
        });
      }
    });
    
    // Sort checked devices by their sort order
    checkedDevices.sort(function(a, b) {
      return a.sortOrder - b.sortOrder;
    });
    
    // Assign sequential sort orders starting from 1
    checkedDevices.forEach(function(device, index) {
      selectedDevices.push(device.id);
      deviceSortOrders[device.id] = index + 1;
    });

    if (type == 'Repair' && selectedDevices.length > 1) {
      Swal.fire({
        title: 'Error!',
        text: 'Please select one device only for repair work order.',
        icon: 'error',
        customClass: {
          confirmButton: 'btn btn-primary waves-effect waves-light'
        },
        buttonsStyling: false
      });
      return;
    }

    if (selectedDevices.length === 0) {
      // Show SweetAlert error message
      Swal.fire({
        title: 'Error!',
        text: 'Please select at least one device.',
        icon: 'error',
        customClass: {
          confirmButton: 'btn btn-primary waves-effect waves-light'
        },
        buttonsStyling: false
      });
      return;
    } else {
      // Show SweetAlert confirmation message
      Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to create a work order for the selected devices.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, create it!',
        cancelButtonText: 'No, cancel!',
        customClass: {
          confirmButton: 'btn btn-primary waves-effect waves-light',
          cancelButton: 'btn btn-secondary waves-effect waves-light'
        },
        buttonsStyling: false
      }).then(result => {
        if (result.isConfirmed) {
          $.ajax({
            url: '/manage-work-orders/store',
            type: 'POST',
            data: {
              devices: selectedDevices,
              device_sort_orders: deviceSortOrders,
              qb: qb,
              client_po: client_po,
              customer_id: cid,
              type: type,
              notes: notes,
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
        }
      });
    }
  });
});
