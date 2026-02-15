/**
 * Page User List
 */

'use strict';

$(function () {
  $('.datatables').DataTable({
    ordering: false,
    searching: false,
    paging: false
  });
  // Filter event handler
  $('.user_role, .user_plan, .user_status').on('change', function () {
    userTable.ajax.reload();
  });

  // Add new user

  // Datatable (jquery)

  let borderColor, bodyBg, headingColor;

  if (isDarkStyle) {
    borderColor = config.colors_dark.borderColor;
    bodyBg = config.colors_dark.bodyBg;
    headingColor = config.colors_dark.headingColor;
  } else {
    borderColor = config.colors.borderColor;
    bodyBg = config.colors.bodyBg;
    headingColor = config.colors.headingColor;
  }

  // Variable declaration for table
  var dt_user_table = $('.datatables-users'),
    select2 = $('.select2'),
    userView = baseUrl + 'manage-users/',
    statusObj = {
      A: { title: 'Active', class: 'bg-label-success' },
      D: { title: 'Inactive', class: 'bg-label-warning' }
    };

  if (select2.length) {
    var $this = select2;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Select Country',
      dropdownParent: $this.parent()
    });
  }

  // Users datatable
  // if (dt_user_table.length)
  var dt_user = dt_user_table.DataTable({
    ajax: {
      url: '/manage-customers/show',
      type: 'GET',
      data: function (d) {
        console.log(d);
        d._token = '{{ csrf_token() }}';
        d.user_role = $('.user_role').val();
        d.user_plan = $('.user_plan').val();
        d.user_status = $('.user_status').val();
      }
    },
    // assetsPath + 'json/user-list.json', // JSON file to add data
    columns: [
      // columns according to JSON
      { data: '' },
      { data: 'company' },
      { data: 'primary_contact' },
      { data: 'primary_phone' },
      { data: 'primary_email' },
      { data: 'secondary_contact' },
      { data: 'secondary_phone' },
      { data: 'secondary_email' },
      { data: 'address' },
      { data: 'pm_type' },
      { data: 'status' },
      { data: 'comment' },
      { data: 'action' }
    ],
    columnDefs: [
      {
        // For Responsive
        className: 'control',
        searchable: false,
        orderable: false,
        responsivePriority: 2,
        targets: 0,
        render: function (data, type, full, meta) {
          return '';
        }
      },
      {
        // User Status
        targets: 1,
        render: function (data, type, full, meta) {
          var $color = full['color'];

          return '<span style="color:' + $color + '" >' + full['company'] + '</span>';
        }
      },
      {
        // User Status
        targets: 2,
        render: function (data, type, full, meta) {
          var $color = full['color'];
          return '<span style="color:' + $color + '" >' + data + '</span>';
        }
      },
      {
        // User Status
        targets: 3,
        render: function (data, type, full, meta) {
          var $color = full['color'];
          return '<span style="color:' + $color + '" >' + data + '</span>';
        }
      },
      {
        // User Status
        targets: 4,
        render: function (data, type, full, meta) {
          var $color = full['color'];
          return '<span style="color:' + $color + '" >' + data + '</span>';
        }
      },
      {
        // User Status
        targets: 10,
        render: function (data, type, full, meta) {
          var $status = full['status'];

          return (
            '<span class="badge ' +
            statusObj[$status].class +
            '" text-capitalized>' +
            statusObj[$status].title +
            '</span>'
          );
        }
      },
      {
        // Actions
        targets: -1,
        title: 'Actions',
        searchable: false,
        orderable: false,
        render: function (data, type, full, meta) {
          var $txt = '';
          if (full['status'] == 'A') {
            $txt = 'Deactivate this user';
          } else {
            $txt = 'Activate this user';
          }
          return (
            '<div class="d-flex align-items-center">' +
            '<a href="/manage-work-orders/create/' +
            full['id'] +
            '" class="text-body add-workorder me-2" title="Add Work Order" data-id="' +
            full['id'] +
            '" >' +
            `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="40" zoomAndPan="magnify" viewBox="0 0 30 30.000001" height="40" preserveAspectRatio="xMidYMid meet" version="1.2"><defs/><g id="353f8210df"><g style="fill:#6f6b7d;fill-opacity:1;"><g transform="translate(2.088118, 18.388732)"><path style="stroke:none" d="M 10.953125 -8.25 C 11.023438 -8.25 11.070312 -8.226562 11.09375 -8.1875 C 11.125 -8.15625 11.128906 -8.109375 11.109375 -8.046875 L 8.828125 -0.171875 C 8.804688 -0.109375 8.773438 -0.0625 8.734375 -0.03125 C 8.703125 -0.0078125 8.648438 0 8.578125 0 L 7.90625 0 C 7.757812 0 7.671875 -0.0507812 7.640625 -0.15625 L 5.859375 -6.46875 C 5.835938 -6.550781 5.8125 -6.546875 5.78125 -6.453125 L 3.984375 -0.171875 C 3.960938 -0.109375 3.929688 -0.0625 3.890625 -0.03125 C 3.859375 -0.0078125 3.804688 0 3.734375 0 L 3.046875 0 C 2.910156 0 2.828125 -0.0507812 2.796875 -0.15625 L 0.53125 -8.046875 C 0.519531 -8.066406 0.515625 -8.085938 0.515625 -8.109375 C 0.515625 -8.203125 0.570312 -8.25 0.6875 -8.25 L 1.40625 -8.25 C 1.507812 -8.25 1.570312 -8.207031 1.59375 -8.125 L 3.40625 -1.640625 C 3.425781 -1.566406 3.445312 -1.53125 3.46875 -1.53125 C 3.5 -1.53125 3.519531 -1.566406 3.53125 -1.640625 L 5.390625 -8.125 C 5.410156 -8.207031 5.472656 -8.25 5.578125 -8.25 L 6.21875 -8.25 C 6.28125 -8.25 6.320312 -8.238281 6.34375 -8.21875 C 6.375 -8.207031 6.398438 -8.175781 6.421875 -8.125 L 8.25 -1.609375 C 8.269531 -1.546875 8.289062 -1.515625 8.3125 -1.515625 C 8.332031 -1.515625 8.351562 -1.546875 8.375 -1.609375 L 10.21875 -8.125 C 10.226562 -8.207031 10.289062 -8.25 10.40625 -8.25 Z M 10.953125 -8.25 "/></g></g><g style="fill:#6f6b7d;fill-opacity:1;"><g transform="translate(13.22398, 18.388732)"><path style="stroke:none" d="M 4.25 0.109375 C 3.519531 0.109375 2.890625 -0.0507812 2.359375 -0.375 C 1.828125 -0.707031 1.421875 -1.1875 1.140625 -1.8125 C 0.859375 -2.445312 0.71875 -3.207031 0.71875 -4.09375 C 0.71875 -4.988281 0.859375 -5.753906 1.140625 -6.390625 C 1.429688 -7.035156 1.84375 -7.523438 2.375 -7.859375 C 2.90625 -8.191406 3.535156 -8.359375 4.265625 -8.359375 C 4.984375 -8.359375 5.609375 -8.191406 6.140625 -7.859375 C 6.671875 -7.523438 7.078125 -7.039062 7.359375 -6.40625 C 7.640625 -5.769531 7.78125 -5.003906 7.78125 -4.109375 C 7.78125 -3.210938 7.640625 -2.445312 7.359375 -1.8125 C 7.078125 -1.1875 6.671875 -0.707031 6.140625 -0.375 C 5.609375 -0.0507812 4.976562 0.109375 4.25 0.109375 Z M 4.265625 -0.703125 C 5.066406 -0.703125 5.664062 -0.976562 6.0625 -1.53125 C 6.457031 -2.09375 6.65625 -2.945312 6.65625 -4.09375 C 6.65625 -5.269531 6.453125 -6.140625 6.046875 -6.703125 C 5.648438 -7.265625 5.054688 -7.546875 4.265625 -7.546875 C 3.453125 -7.546875 2.84375 -7.257812 2.4375 -6.6875 C 2.03125 -6.125 1.828125 -5.257812 1.828125 -4.09375 C 1.828125 -2.945312 2.03125 -2.09375 2.4375 -1.53125 C 2.84375 -0.976562 3.453125 -0.703125 4.265625 -0.703125 Z M 4.265625 -0.703125 "/></g></g><g style="fill:#6f6b7d;fill-opacity:1;"><g transform="translate(21.714971, 18.388732)"><path style="stroke:none" d="M 5.40625 -4.515625 C 5.476562 -4.515625 5.515625 -4.476562 5.515625 -4.40625 L 5.515625 -3.921875 C 5.515625 -3.828125 5.457031 -3.78125 5.34375 -3.78125 L 3.484375 -3.78125 L 3.484375 -1.84375 C 3.484375 -1.769531 3.445312 -1.734375 3.375 -1.734375 L 2.890625 -1.734375 C 2.785156 -1.734375 2.734375 -1.789062 2.734375 -1.90625 L 2.734375 -3.765625 L 0.828125 -3.765625 C 0.734375 -3.765625 0.6875 -3.8125 0.6875 -3.90625 L 0.6875 -4.390625 C 0.6875 -4.472656 0.722656 -4.515625 0.796875 -4.515625 L 2.734375 -4.515625 L 2.734375 -6.421875 C 2.734375 -6.472656 2.742188 -6.507812 2.765625 -6.53125 C 2.785156 -6.550781 2.816406 -6.5625 2.859375 -6.5625 L 3.34375 -6.5625 C 3.425781 -6.5625 3.46875 -6.53125 3.46875 -6.46875 L 3.46875 -4.515625 Z M 5.40625 -4.515625 "/></g></g><g style="fill:#6f6b7d;fill-opacity:1;"><g transform="translate(27.91095, 18.388732)"><path style="stroke:none" d=""/></g></g></g></svg>` +
            '</a>' +
            '<a href="/manage-customers/' +
            full['id'] +
            '/edit' +
            '" class="text-body"><i class="ti ti-edit ti-sm me-2"></i></a>' +
            '<a href="javascript:;" class="text-body delete-record" data-id="' +
            full['id'] +
            '" ><i class="ti ti-trash ti-sm mx-2"></i></a>' +
            '<a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>' +
            '<div class="dropdown-menu dropdown-menu-end m-0">' +
            '<a href="javascript:;" class="dropdown-item update-status" data-id="' +
            full['id'] +
            '">' +
            $txt +
            '</a>' +
            '</div>' +
            '</div>'
          );
        }
      }
    ],

    order: [[1, 'desc']],
    paging: false,
    dom:
      '<"row me-2"' +
      '<"col-md-2"<"me-3"l>>' +
      '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
      '>' +
      'r' + // ðŸ‘ˆ Added here to enable processing animation
      't' +
      '<"row mx-2"' +
      '<"col-sm-12 col-md-6"i>' +
      '<"col-sm-12 col-md-6"p>' +
      '>',
    language: {
      sLengthMenu: '_MENU_',
      search: '',
      searchPlaceholder: 'Search..'
    },
    // Buttons with Dropdown
    buttons: [
      {
        extend: 'collection',
        className: 'btn btn-label-secondary dropdown-toggle mx-3 waves-effect waves-light',
        text: '<i class="ti ti-screen-share me-1 ti-xs"></i>Export',
        buttons: [
          {
            extend: 'csv',
            text: '<i class="ti ti-file-text me-2" ></i>Csv',
            className: 'dropdown-item',
            exportOptions: {
              columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
              // prevent avatar to be display
              format: {
                body: function (inner, coldex, rowdex) {
                  if (inner.length <= 0) return inner;
                  var el = $.parseHTML(inner);
                  var result = '';
                  $.each(el, function (index, item) {
                    if (item.classList !== undefined && item.classList.contains('user-name')) {
                      result = result + item.lastChild.firstChild.textContent;
                    } else if (item.innerText === undefined) {
                      result = result + item.textContent;
                    } else result = result + item.innerText;
                  });
                  return result;
                }
              }
            }
          },
          {
            extend: 'excel',
            text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
            className: 'dropdown-item',
            exportOptions: {
              columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
              // prevent avatar to be display
              format: {
                body: function (inner, coldex, rowdex) {
                  if (inner.length <= 0) return inner;
                  var el = $.parseHTML(inner);
                  var result = '';
                  $.each(el, function (index, item) {
                    if (item.classList !== undefined && item.classList.contains('user-name')) {
                      result = result + item.lastChild.firstChild.textContent;
                    } else if (item.innerText === undefined) {
                      result = result + item.textContent;
                    } else result = result + item.innerText;
                  });
                  return result;
                }
              }
            }
          },

          {
            extend: 'copy',
            text: '<i class="ti ti-copy me-2" ></i>Copy',
            className: 'dropdown-item',
            exportOptions: {
              columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
              // prevent avatar to be display
              format: {
                body: function (inner, coldex, rowdex) {
                  if (inner.length <= 0) return inner;
                  var el = $.parseHTML(inner);
                  var result = '';
                  $.each(el, function (index, item) {
                    if (item.classList !== undefined && item.classList.contains('user-name')) {
                      result = result + item.lastChild.firstChild.textContent;
                    } else if (item.innerText === undefined) {
                      result = result + item.textContent;
                    } else result = result + item.innerText;
                  });
                  return result;
                }
              }
            }
          }
        ]
      },
      {
        text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Import Customers</span>',
        className: 'add-new btn btn-secondary waves-effect waves-light',
        attr: {
          'data-bs-toggle': 'modal',
          'data-bs-target': '#enableOTP'
        }
      },
      {
        text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs "></i><span class="d-none d-sm-inline-block">Add Customers</span>',
        className: 'add-new btn btn-primary waves-effect waves-light ms-2',
        attr: {
          onclick: 'window.location.href="manage-customers/add"'
        }
      }
    ],
    // For responsive popup
    responsive: {
      details: {
        display: $.fn.dataTable.Responsive.display.modal({
          header: function (row) {
            var data = row.data();
            return 'Details of ' + data['company'];
          }
        }),
        type: 'column',
        renderer: function (api, rowIdx, columns) {
          var data = $.map(columns, function (col, i) {
            return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
              ? '<tr data-dt-row="' +
                  col.rowIndex +
                  '" data-dt-column="' +
                  col.columnIndex +
                  '">' +
                  '<td>' +
                  col.title +
                  ':' +
                  '</td> ' +
                  '<td>' +
                  col.data +
                  '</td>' +
                  '</tr>'
              : '';
          }).join('');

          return data ? $('<table class="table"/><tbody />').append(data) : false;
        }
      }
    },
    initComplete: function () {
      // Adding role filter once table initialized
      this.api()
        .columns(1)
        .every(function () {
          var column = this;
          var select = $(
            '<select id="UserRole" class="form-select text-capitalize user-company"><option value=""> Select Company </option></select>'
          )
            .appendTo('.user_role')
            .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());
              column.search(val ? '^' + val + '$' : '', true, false).draw();
            });

          column
            .data()
            .unique()
            .sort()
            .each(function (d, j) {
              select.append('<option value="' + d + '">' + d + '</option>');
            });
        });
      // Adding plan filter once table initialized

      // Adding status filter once table initialized
      this.api()
        .columns(10)
        .every(function () {
          var column = this;
          var select = $(
            '<select id="FilterTransaction" class="form-select text-capitalize user-status"><option value=""> Select Status </option></select>'
          )
            .appendTo('.user_status')
            .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());
              column.search(val ? '^' + val + '$' : '', true, false).draw();
            });

          select.append(
            '<option value="' +
              statusObj['A'].title +
              '" class="text-capitalize">' +
              statusObj['A'].title +
              '</option>' +
              '<option value="' +
              statusObj['D'].title +
              '" class="text-capitalize">' +
              statusObj['D'].title +
              '</option>'
          );
          var resetButton = $('<button class="btn btn-warning ms-2">Reset Filters</button>')
            .appendTo('.todo_filter')
            .on('click', function () {
              // Reset the select elements
              $('.user-company').val('').trigger('change');
              $('.user-status').val('').trigger('change');

              // Redraw the DataTable
              // dt_user.ajax.reload();
            });
          // column
          //   .data()
          //   .unique()
          //   .sort()
          //   .each(function (d, j) {
          //     select.append(
          //       '<option value="' + statusObj[d].title + '" class="text-capitalize">' + statusObj[d].title + '</option>'
          //     );
          //   });
        });

      $('#UserPlan').trigger('change');
    }
  });

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var id = $(this).data('id');
    var $row = $(this).closest('tr');
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'No, cancel!'
    }).then(function (result) {
      if (result.isConfirmed) {
        $.ajax({
          url: '/manage-customers/delete/' + id,
          type: 'get',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            // Handle success response
            if (response.status) {
              // Show SweetAlert success message
              toastr.success(response.message, 'Success', {
                closeButton: true,
                progressBar: true,
                timeOut: 2500,
                positionClass: 'toast-top-right'
              });
              //while responsive close the modal

              // Remove the row from the DataTable
              dt_user.row($row).remove().draw();
              $('.btn-close').click();
            } else {
              // Show SweetAlert error message
              toastr.error(response.message, 'Error', {
                closeButton: true,
                progressBar: true,
                timeOut: 2500,
                positionClass: 'toast-top-right'
              });
            }
          },
          error: function (xhr, status, error) {
            // Handle error response
            toastr.error('Technical error', 'Error', {
              closeButton: true,
              progressBar: true,
              timeOut: 3000,
              positionClass: 'toast-top-right'
            });
          }
        });
      }
    });
  });

  //update status
  $(document).on('click', '.update-status', function () {
    var id = $(this).data('id');
    $.ajax({
      url: '/manage-customers/update-status/' + id,
      type: 'get',
      success: function (response) {
        // Handle success response
        if (response.status) {
          // Show SweetAlert success message
          toastr.success(response.message, 'Success', {
            closeButton: true,
            progressBar: true,
            timeOut: 2500,
            positionClass: 'toast-top-right'
          });

          // Reload the DataTable
          dt_user.ajax.reload();
        } else {
          // Show SweetAlert error message
          toastr.error(response.message, 'Error', {
            closeButton: true,
            progressBar: true,
            timeOut: 2500,
            positionClass: 'toast-top-right'
          });
        }
      },
      error: function (xhr, status, error) {
        // Handle error response
        toastr.error('Technical error', 'Error', {
          closeButton: true,
          progressBar: true,
          timeOut: 3000,
          positionClass: 'toast-top-right'
        });
      }
    });
  });

  // const importForm = document.getElementById('importForm');

  // // Phone Number
  // FormValidation.formValidation(importForm, {
  //   fields: {
  //     file: {
  //       validators: {
  //         notEmpty: {
  //           message: 'Please enter Company name '
  //         }
  //       }
  //     }
  //   },
  //   plugins: {
  //     trigger: new FormValidation.plugins.Trigger(),
  //     bootstrap5: new FormValidation.plugins.Bootstrap5({
  //       // Use this for enabling/changing valid/invalid class
  //       eleValidClass: '',
  //       rowSelector: function () {
  //         return '.mb-3';
  //       }
  //     }),
  //     submitButton: new FormValidation.plugins.SubmitButton(),
  //     autoFocus: new FormValidation.plugins.AutoFocus()
  //   }
  // }).on('core.form.valid', function () {
  //   // Submit the form when valid
  //   alert();
  //   $('#importForm').submit();
  // });
  // Add New User Form Validation
});
