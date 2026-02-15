'use strict';

$(function () {
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
  var dt_user_table = $('.datatables-tasks'),
    select2 = $('.select2'),
    userView = baseUrl + 'app/user/view/account',
    statusObj = {
      Normal: { title: 'Normal', class: 'bg-label-success' },
      Urgent: { title: 'Urgent', class: 'bg-label-warning' }
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
    processing: true,
    serverSide: true,

    ajax: {
      url: 'tasks/show?task_status=Open',
      type: 'GET',
      data: function (d) {
        d._token = '{{ csrf_token() }}';

        d.company_id = $('.update-company').val();
        d.task_status = $('.status-filter').val();
      }
    },
    // assetsPath + 'json/user-list.json', // JSON file to add data
    columns: [
      // columns according to JSON
      { data: '' },
      // { data: 'id' },
      // { data: 'username' },
      { data: 'company' },
      { data: 'description' },
      { data: 'priority' },
      { data: 'date_added' },
      { data: 'dt_last_activity' },
      { data: 'status' },
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
      // {
      //   // User full name and email
      //   targets: 2,
      //   responsivePriority: 4,
      //   render: function (data, type, full, meta) {
      //     var $name = full['user'];

      //     var $row_output =
      //       '<div class="d-flex justify-content-start align-items-center user-name">' +
      //       // '<div class="avatar-wrapper">' +
      //       // '<div class="avatar me-3">' +
      //       // $output +
      //       // '</div>' +
      //       // '</div>' +
      //       '<div class="d-flex flex-column">' +
      //       $name.firstname +
      //       ' ' +
      //       $name.lastname +
      //       '</div>' +
      //       '</div>';
      //     return $row_output;
      //   }
      // },

      {
        // Plans
        targets: 1,
        responsivePriority: 1,
        render: function (data, type, full, meta) {
          var $company = full['company'];

          return '<span class="fw-medium">' + ($company ? $company.name : '') + '</span>';
        }
      },
      {
        // Plans
        targets: 2,
        responsivePriority: 2,
        render: function (data, type, full, meta) {
          var $description = full['short_desc'];
          var $device = full['device_affected'];
          var $ip = full['ip_address'];
          var $output =
            '<div class=" flex-column"><b>Device: </b>' +
            $device +
            '<br><b>Desc: </b>' +
            $description +
            '<br><b>IP: </b>' +
            $ip +
            '</div>';
          return '<span class="fw-medium">' + $output + '</span>';
        }
      },
      //extra mail
      {
        // Plans
        targets: 3,
        render: function (data, type, full, meta) {
          var $enumToDo = full['enumToDo'];

          return (
            '<span class="badge ' +
            statusObj[$enumToDo].class +
            '" text-capitalized>' +
            statusObj[$enumToDo].title +
            '</span>'
          );
        }
      },
      {
        // User Status
        targets: 4,
        responsivePriority: 3,
        render: function (data, type, full, meta) {
          var $date = full['created_at'];
          // var $date = '11 Jan 2025 <br> 10:45:17 AM';
          return moment($date).format('YYYY-MM-DD hh:mm:ss A');
        }
      },
      {
        // User Status
        targets: 5,
        render: function (data, type, full, meta) {
          var $date = full['updated_at'];
          // var $date = '11 Jan 2025 <br> 10:45:17 AM';

          return moment($date).format('YYYY-MM-DD hh:mm:ss A');
        }
      },
      {
        // User Status
        targets: 6,
        render: function (data, type, full, meta) {
          var $status = full['status'];
          // enum('Open', 'Closed', 'Archived', 'Pending'), select option for these statuses
          var $output =
            '<select class="form-select text-capitalize update-status" data-id="' +
            full['id'] +
            '" id="status" name="status" aria-label="Status">' +
            '<option value="Open"' +
            ($status === 'Open' ? ' selected' : '') +
            '>Open</option>' +
            '<option value="Closed"' +
            ($status === 'Closed' ? ' selected' : '') +
            '>Closed</option>' +
            '<option value="Archived"' +
            ($status === 'Archived' ? ' selected' : '') +
            '>Archived</option>' +
            '<option value="Pending"' +
            ($status === 'Pending' ? ' selected' : '') +
            '>Pending</option>' +
            '</select>';
          return $output;
        }
      },
      {
        // Actions
        targets: -1,
        title: 'Actions',
        searchable: false,
        orderable: false,
        render: function (data, type, full, meta) {
          var id = full['id'];
          var loc = "'/manage-tasks/print/" + id + "','Print','width=650,height=650'";
          return (
            '<div class="d-flex align-items-center text-secondary">' +
            '<a href="javascript:;" onclick="window.open(' +
            loc +
            ');" class="text-secondary print-record"><i class="ti ti-printer ti-sm me-2"></i></a>' +
            '<a href="/manage-tasks/' +
            id +
            '/edit" class="text-primary"><i class="ti ti-edit ti-sm me-2"></i></a>' +
            '<a href="javascript:;" class="text-body open-record"><i class="ti ti-list ti-sm mx-2"></i></a>' +
            '<a href="javascript:;" class="text-danger delete-record" data-id="' +
            full['id'] +
            '"><i class="ti ti-trash ti-sm mx-2"></i></a>' +
            // '<a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>' +
            // '<div class="dropdown-menu dropdown-menu-end m-0">' +
            // '<a href="javascript:;" class="dropdown-item control">' +
            // 'View Details' +
            // '</a>' +
            // '</div>' +
            '</div>'
          );
        }
      }
    ],
    order: [[4, 'desc']],
    paging: false,
    dom:
      '<"row me-2"' +
      '<"col-md-2"<"me-3"l>>' +
      '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
      '>' +
      'r' + // 👈 Added here to enable processing animation
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
              columns: [1, 2, 3, 4, 5],
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
              columns: [1, 2, 3, 4, 5],
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
              columns: [1, 2, 3, 4, 5],
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
        text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add New Task</span>',
        className: 'add-new btn btn-primary waves-effect waves-light',
        action: function () {
          window.location.href = 'manage-tasks/create';
        }
      }
    ],
    // For responsive popup
    responsive: {
      details: {
        display: $.fn.dataTable.Responsive.display.modal({
          header: function (row) {
            var data = row.data();

            return 'Details of ' + data['short_desc'];
          }
        }),
        type: 'column',
        renderer: function (api, rowIdx, columns) {
          var dataall = api.data();
          var data = $.map(columns, function (col, i) {
            var result = '';
            //console.log(columns.length + ' : ' + col.columnIndex);
            if (columns.length == col.columnIndex) {
              result =
                '<tr><td>' + 'Long Description' + ':' + '</td> ' + '<td>' + dataall[rowIdx]['long_desc'] + '</td></tr>';
            }

            result =
              result +
              (col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
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
                : '');
            return result;
          }).join('');

          return data ? $('<table class="table"/><tbody />').append(data) : false;
        }
      }
    },
    initComplete: function () {
      // this.api()
      //   .columns(5)
      //   .every(function () {
      //     var column = this;
      //     var select = $(
      //       '<select id="UserRole" class="form-select text-capitalize"><option value=""> Select Role </option></select>'
      //     )
      //       .appendTo('.task_status')
      //       .on('change', function () {
      //         var val = $.fn.dataTable.util.escapeRegex($(this).val());
      //         column.search(val ? '^' + val + '$' : '', true, false).draw();
      //       });
      //     console.log(column.data());
      //     column
      //       .data()
      //       .unique()
      //       .sort()
      //       .each(function (d, j) {
      //         select.append('<option value="' + d + '">' + d + '</option>');
      //       });
      //   });
      // Adding plan filter once table initialized
      this.api()
        .columns(1)
        .every(function () {
          var column = this;
          var select = $(
            '<select id="UserPlan" class="form-select text-capitalize update-company"><option value="" disabled> Select Company</option></select>'
          )
            .appendTo('.task_company')
            .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());
              column.search(val ? '^' + val + '$' : '', true, false).draw();
            });

          const uniqueNames = new Set();
          column
            .data()
            .unique()
            .sort()
            .each(function (d) {
              if (d && d.name && !uniqueNames.has(d.name)) {
                uniqueNames.add(d.name);
                select.append('<option value="' + d.id + '">' + d.name + '</option>');
              }
            });
          select.append('<option value="" selected>All Companies</option>');
        });

      // Adding status filter once table initialized
      this.api()
        .columns(6)
        .every(function () {
          var column = this;
          var select = $(
            '<select id="FilterTransaction" class="form-select text-capitalize status-filter"><option value="" disabled> Select Status </option>' +
              '<option value="Open" selected>Open/Pending</option>' +
              '<option value="Closed">Closed</option>' +
              '<option value="Archived">Archived</option>' +
              '<option value="">All</option>' +
              '</select>'
          )
            .appendTo('.task_status')
            .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());
              column.search(val ? '^' + val + '$' : '', true, false).draw();
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

      //add butto to reset the filter

      // Add a button to reset the filter
      var resetButton = $('<button class="btn btn-warning ms-2">Reset Filters</button>')
        .appendTo('.task_filter')
        .on('click', function () {
          // Reset the select elements

          $('#FilterTransaction').val('Open').trigger('change');
          $('.update-company').val('').trigger('change');

          // Redraw the DataTable
          // dt_user.ajax.reload();
        });
    }
  });

  // Show/hide description row
  $('.datatables-tasks tbody').on('click', '.open-record', function () {
    var tr = $(this).closest('tr');
    var row = dt_user.row(tr);

    if (row.child.isShown()) {
      row.child.hide();
      tr.removeClass('shown');
    } else {
      var descriptionHtml = row.data().long_desc || '';
      var $descDiv = $('<div class="p-3 bg-light border rounded"></div>');
      $descDiv.html(descriptionHtml); // Safely inject HTML

      row.child($descDiv).show();
      tr.addClass('shown');
    }
  });
  $('.update-company, .update-status , .status-filter').on('change', function () {
    dt_user.ajax.reload();
  });

  $('.datatables-tasks tbody').on('click', 'tr', function () {
    dt_user.row(this).edit();
  });
  // Delete Record
  $('.datatables-tasks tbody').on('click', '.delete-record', function () {
    var id = $(this).data('id'); // Get the ID from the second column (index 1)
    var $row = $(this).closest('tr');
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: false,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'No, cancel!'
    }).then(function (result) {
      if (result.isConfirmed) {
        $.ajax({
          url: '/manage-tasks/delete/' + id,
          type: 'get',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function (response) {
            if (response.status) {
              dt_user.row($row).remove().draw();
              toastr.success(response.message, 'Success', {
                closeButton: true,
                progressBar: true,
                timeOut: 3000,
                positionClass: 'toast-top-right'
              });
            } else {
              toastr.error(response.message, 'Error', {
                closeButton: true,
                progressBar: true,
                timeOut: 3000,
                positionClass: 'toast-top-right'
              });
            }
          },
          error: function () {
            toastr.error('Technical error', 'Error', {
              closeButton: true,
              progressBar: true,
              timeOut: 3500,
              positionClass: 'toast-top-right'
            });
          }
        });
      }
    });
  });

  // Update status
  $('.datatables-tasks tbody').on('change', '.update-status', function () {
    var status = $(this).val();
    var id = $(this).data('id'); // Get the ID from the second column (index 1)

    if (status == 'Archived') {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: false,
        confirmButtonText: 'Yes, Archive it!',
        cancelButtonText: 'No, cancel!'
      }).then(function (result) {
        if (result.isConfirmed) {
          changeStatus(id, status);
        } else {
          dt_user.ajax.reload();
        }
      });
    } else {
      changeStatus(id, status);
    }
  });

  $('#addNewUserForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission
    var formData = new FormData(this);
    $.ajax({
      url: "{{ route('user-add') }}",
      type: 'POST',
      data: formData,
      success: function (response) {
        if (true) {
          // Close the offcanvas
          $('#offcanvasAddUser').modal.dismiss();
          // $('#offcanvasAddUser').addClass('offcanvas-end');
          $('#addNewUserForm').trigger('reset');

          // Reload the DataTable
          dt_user.ajax.reload();

          // Show SweetAlert success message
          Swal.fire({
            title: 'Success!',
            text: 'User added successfully.',
            icon: 'success',
            confirmButtonText: 'OK'
          });
        }
      },
      cache: false,
      contentType: false,
      processData: false,
      error: function () {
        // Show SweetAlert error message
        Swal.fire({
          title: 'Error!',
          text: 'Something went wrong. Please try again.',
          icon: 'error',
          confirmButtonText: 'OK'
        });
      }
    });
  });

  function changeStatus(id, status) {
    $.ajax({
      url: '/manage-tasks/update-status/' + id,
      type: 'get',
      data: {
        id: id,
        status: status,
        _token: '{{ csrf_token() }}'
      },
      success: function (response) {
        // Handle success response
        if (response.status) {
          // Show SweetAlert success message
          toastr.success(response.message, 'Success', {
            closeButton: true,
            progressBar: true,
            timeOut: 2000,
            positionClass: 'toast-top-right'
          });
        } else {
          // Show SweetAlert error message
          toastr.error(response.message, 'Error', {
            closeButton: true,
            progressBar: true,
            timeOut: 2000,
            positionClass: 'toast-top-right'
          });
        }
      },
      error: function (xhr, status, error) {
        // Handle error response
        toastr.error('Technical error', 'Error', {
          closeButton: true,
          progressBar: true,
          timeOut: 2000,
          positionClass: 'toast-top-right'
        });
      }
    });
  }
  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});

// Validation & Phone mask
(function () {
  const phoneMaskList = document.querySelectorAll('.phone-mask'),
    addNewUserForm = document.getElementById('addNewUserForm');

  // Phone Number
  if (phoneMaskList) {
    phoneMaskList.forEach(function (phoneMask) {
      new Cleave(phoneMask, {
        phone: true,
        phoneRegionCode: 'US'
      });
    });
  }
  // Add New User Form Validation
  const fv = FormValidation.formValidation(addNewUserForm, {
    fields: {
      firstName: {
        validators: {
          notEmpty: {
            message: 'Please enter fullname '
          }
        }
      },
      userEmail: {
        validators: {
          notEmpty: {
            message: 'Please enter your email'
          },
          emailAddress: {
            message: 'The value is not a valid email address'
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
  });
})();

(function () {
  var addEmails = 1;
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
  $('#dateFrom, #dateTo').datepicker({
    dateFormat: 'yy-mm-dd'
  });
  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var min = $('#dateFrom').val() ? new Date($('#dateFrom').val()) : null;
    var max = $('#dateTo').val() ? new Date($('#dateTo').val()) : null;
    var dateEntered = new Date(data[6]); // Assuming the "Date Entered" column is at index 6

    if (
      (min === null && max === null) ||
      (min === null && dateEntered <= max) ||
      (min <= dateEntered && max === null) ||
      (min <= dateEntered && dateEntered <= max)
    ) {
      return true;
    }
    return false;
  });

  // Trigger DataTable redraw on date change
  $('#dateFrom, #dateTo').on('change', function () {
    dt_user.draw();
  });

  //make function for date range
})();
