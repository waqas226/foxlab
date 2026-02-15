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
    userView = baseUrl + 'manage-devices/',
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
      url: '/manage-devices/show',
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

      { data: 'device_type' },
      { data: 'make' },
      { data: 'model' },
      { data: 'sn' },
      { data: 'asset' },
      { data: 'last_pm' },
      { data: 'next_pm' },
      { data: 'company' },
      { data: 'checklist' },
      { data: 'actions' }
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
        targets: 7,

        render: function (data, type, full, meta) {
          var current_month = new Date().getMonth() + 1;
          var current_year = new Date().getFullYear();

          var tododate = full['next_pm'];
          if (typeof tododate === 'string' && !isNaN(Date.parse(tododate))) {
            var parts = tododate.split('-'); // "2025-05-09" → [2025, 05, 09]
            var date = new Date(parts[0], parts[1], parts[2]); // Note: month is 0-indexed
            console.log(current_month + ' : ' + parts[1]);
            if (parseInt(parts[0]) < current_year) {
              //change the color of the parent tr
              return `<span style="color: red;">${tododate}</span>`;
            } else if (parseInt(parts[0]) > current_year) {
              //change the color of the parent tr
              return `<span style="color: blue;">${tododate}</span>`;
            } else if (parseInt(parts[1]) === current_month) {
              //change the color of the parent tr
              return `<span style="color: black;">${tododate}</span>`;
            } else if (parts[1] < current_month) {
              return `<span style="color: red;">${tododate}</span>`;
            } else {
              return `<span style="color: blue;">${tododate}</span>`;
            }
          } else {
            return `<span style="color: red;">${tododate}</span>`;
          }
        }
      },
      {
        targets: 9,
        visible: false // Hide the checklist column
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
            '<a href="/manage-devices/' +
            full['id'] +
            '/edit' +
            '" class="text-primary " title="Edit"><i class="ti ti-edit ti-sm me-2"></i></a>' +
            '<a href="/device-history/' +
            full['id'] +
            '" class="text-info" title="View History"><i class="ti ti-history ti-sm me-2"></i></a>' +
            '<a href="javascript:;" class="text-danger delete-record" title="Delete" data-id="' +
            full['id'] +
            '" ><i class="ti ti-trash ti-sm mx-2"></i></a>' +
            '</div>'
          );
        }
      }
    ],
    order: [[1, 'asc']],
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
              columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
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
              columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
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
              columns: [1, 2, 3, 4, 5, 6, 7, 8],
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
        text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Import Devices</span>',
        className: 'add-new btn btn-secondary waves-effect waves-light',
        attr: {
          'data-bs-toggle': 'modal',
          'data-bs-target': '#enableOTP'
        }
      },
      {
        text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add Devices</span>',
        className: 'add-new btn btn-primary waves-effect waves-light ms-2',
        attr: {
          onclick: 'window.location.href="/manage-devices/add"'
        }
      }
    ],
    // For responsive popup
    responsive: {
      details: {
        display: $.fn.dataTable.Responsive.display.modal({
          header: function (row) {
            var data = row.data();
            return 'Details of ' + data['make'] + ' ' + data['model'];
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
        .columns(8)
        .every(function () {
          var column = this;
          var select = $('#deviceCompany').on('change', function () {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            column.search(val ? '^' + val + '$' : '', true, false).draw();
          });

          // column
          //   .data()
          //   .unique()
          //   .sort()
          //   .each(function (d, j) {
          //     select.append('<option value="' + d + '">' + d + '</option>');
          //   });
        });
      // Adding plan filter once table initialized

      // Adding status filter once table initialized
      this.api()
        .columns(7)
        .every(function () {
          var column = this;
          $(
            '<select id="FilterTransaction" class="form-select text-capitalize user-status">' +
              '<option value=""> Select Due in</option>' +
              '<option value="1">1 Month</option>' +
              '<option value="2">2 Months</option>' +
              '<option value="3">3 Months</option>' +
              '</select>'
          )
            .appendTo('.user_status')
            .on('change', function () {
              // redraw on change
              dt_user.ajax.reload();
            });
        });

      // 2. Attach DataTables filtering logic
      $.fn.dataTable.ext.search.push(function (settings, rowData) {
        var selected = $('#FilterTransaction').val(); // "1", "2", "3", or ""
        if (!selected) return true; // no filter, show all

        var dueDateStr = rowData[7]; // e.g., "2025-06-24"
        var due = new Date(dueDateStr);
        if (isNaN(due)) return false; // invalid date

        var now = new Date();
        var limit = new Date(now.getFullYear(), now.getMonth() + parseInt(selected), now.getDate());

        return due <= limit;
      });

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
          url: '/manage-devices/delete/' + id,
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

              // Remove the row from the DataTable
              dt_user.row($row).remove().draw();
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
      url: '/manage-devices/update-status/' + id,
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
