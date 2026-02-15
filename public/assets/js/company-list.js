/**
 * Page User List
 */

'use strict';

$(function () {
  // Filter event handler

  // Add new user

  $('#addNewCompanyForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission
    var formData = new FormData(this);
    $.ajax({
      url: 'manage-company/store',
      type: 'POST',
      data: formData,
      success: function (response) {
        dt_user.ajax.reload();
        var offcanvasElement = document.getElementById('offcanvasAddCompany');
        var offcanvasInstance =
          bootstrap.Offcanvas.getInstance(offcanvasElement) || new bootstrap.Offcanvas(offcanvasElement);
        offcanvasInstance.hide();
        $('#addNewCompanyForm').trigger('reset');
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: 'Company added successfully.',
          showConfirmButton: false,
          timer: 2500,
          customClass: {
            confirmButton: 'btn btn-primary waves-effect waves-light'
          },
          buttonsStyling: false
        });
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseJSON);
        var errors = xhr.responseJSON.errors;
        if (errors) {
          if (errors.name) {
            $('#name').addClass('is-invalid');

            // <div data-field="name" data-validator="notEmpty">Please enter Company name </div>  add this div in html
            $('#name')
              .closest('.mb-3')
              .find('.invalid-feedback')
              .html('<div data-field="name" data-validator="notEmpty">' + errors.name[0] + '</div>');
          }
        }
      },
      cache: false,
      contentType: false,
      processData: false
    });
  });

  //update user
  $('#UpdateCompanyForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission
    var formData = new FormData(this);
    $.ajax({
      url: 'manage-company/store',
      type: 'POST',
      data: formData,
      success: function (response) {
        dt_user.ajax.reload();
        var offcanvasElement = document.getElementById('offcanvasUpdateCompany');
        var offcanvasInstance =
          bootstrap.Offcanvas.getInstance(offcanvasElement) || new bootstrap.Offcanvas(offcanvasElement);
        offcanvasInstance.hide();
        $('#UpdateCompanyForm').trigger('reset');
        toastr.success('Company updated successfully.', 'Success', {
          closeButton: true,
          progressBar: true,
          timeOut: 1500,
          positionClass: 'toast-top-right'
        });
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseJSON);
        var errors = xhr.responseJSON.errors;
        if (errors) {
          if (errors.name) {
            $('#recordname').addClass('is-invalid');

            // <div data-field="name" data-validator="notEmpty">Please enter Company name </div>  add this div in html
            $('#recordname')
              .closest('.mb-3')
              .find('.invalid-feedback')
              .html('<div data-field="name" data-validator="notEmpty">' + errors.name[0] + '</div>');
          }
        }
      },
      cache: false,
      contentType: false,
      processData: false
    });
  });

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
  var dt_user_table = $('.datatables-company'),
    select2 = $('.select2'),
    userView = baseUrl + 'app/user/view/account',
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
  if (dt_user_table.length) {
    var dt_user = dt_user_table.DataTable({
      ajax: {
        url: 'manage-company/show',
        type: 'GET',
        data: function (d) {
          console.log(d);
          d._token = '{{ csrf_token() }}';
          d.user_status = $('.user_status').val();
        }
      },
      pageLength: 50,
      // assetsPath + 'json/user-list.json', // JSON file to add data
      columns: [
        // columns according to JSON
        { data: '' },

        { data: 'name' },
        { data: 'date_added' },
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
        {
          // User full name and email
          targets: 1,
          responsivePriority: 1,
          orderable: true,

          render: function (data, type, full, meta) {
            var $name = full['name'];

            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center user-name">' +
              // '<div class="avatar-wrapper">' +
              // '<div class="avatar me-3">' +
              // $output +
              // '</div>' +
              // '</div>' +
              $name +
              '</div>';
            return $row_output;
          }
        },
        // {
        //   // User Role
        //   targets: 2,
        //   render: function (data, type, full, meta) {
        //     var $role = full['role'];
        //     var roleBadgeObj = {
        //       Subscriber:
        //         '<span class="badge badge-center rounded-pill bg-label-warning w-px-30 h-px-30 me-2"><i class="ti ti-user ti-sm"></i></span>',
        //       Author:
        //         '<span class="badge badge-center rounded-pill bg-label-success w-px-30 h-px-30 me-2"><i class="ti ti-circle-check ti-sm"></i></span>',
        //       Maintainer:
        //         '<span class="badge badge-center rounded-pill bg-label-primary w-px-30 h-px-30 me-2"><i class="ti ti-chart-pie-2 ti-sm"></i></span>',
        //       Editor:
        //         '<span class="badge badge-center rounded-pill bg-label-info w-px-30 h-px-30 me-2"><i class="ti ti-edit ti-sm"></i></span>',
        //       Admin:
        //         '<span class="badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30 me-2"><i class="ti ti-device-laptop ti-sm"></i></span>'
        //     };
        //     return "<span class='text-truncate d-flex align-items-center'>" + roleBadgeObj[$role] + $role + '</span>';
        //   }
        // },

        {
          // User Status
          targets: 2,
          render: function (data, type, full, meta) {
            var $date = full['created_at'];

            return moment($date).format('YYYY-MM-DD');
          }
        },
        {
          // User Status
          targets: 3,
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
            var $sts = 'A';
            if (full['status'] == 'A') {
              $txt = 'Deactivate this Company';
              $sts = 'D';
            } else {
              $txt = 'Activate this Company';
              $sts = 'A';
            }
            return (
              '<div class="d-flex align-items-center">' +
              '<a href="javascript:;" class="text-body update-record" data-id="' +
              full['id'] +
              '"><i class="ti ti-edit ti-sm me-2"></i></a>' +
              '<a href="javascript:;" class="text-body delete-record" data-id="' +
              full['id'] +
              '"><i class="ti ti-trash ti-sm mx-2"></i></a>' +
              '<a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="javascript:;" class="dropdown-item status-update" data-id="' +
              full['id'] +
              '" data-value="' +
              $sts +
              '"  >' +
              $txt +
              '</a>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],

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
                columns: [1, 2, 3, 4],
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
                columns: [1, 2, 3, 4],
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
                columns: [1, 2, 3, 4],
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
          text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add New Company</span>',
          className: 'add-new btn btn-primary waves-effect waves-light',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasAddCompany'
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['name'];
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
        // this.api()
        //   .columns(2)
        //   .every(function () {
        //     var column = this;
        //     var select = $(
        //       '<select id="UserRole" class="form-select text-capitalize"><option value=""> Select Role </option></select>'
        //     )
        //       .appendTo('.user_role')
        //       .on('change', function () {
        //         var val = $.fn.dataTable.util.escapeRegex($(this).val());
        //         column.search(val ? '^' + val + '$' : '', true, false).draw();
        //       });

        //     column
        //       .data()
        //       .unique()
        //       .sort()
        //       .each(function (d, j) {
        //         select.append('<option value="' + d + '">' + d + '</option>');
        //       });
        //   });
        // Adding plan filter once table initialized

        // Adding status filter once table initialized
        this.api()
          .columns(3)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="FilterTransaction" class="form-select text-capitalize"><option value="" disabled> Select Status </option><option value="">All</option><option>Active</option><option>Inactive</option></select>'
            )
              .appendTo('.user_status')
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
            //       '<option value="' +
            //         statusObj[d].title +
            //         '" class="text-capitalize">' +
            //         statusObj[d].title +
            //         '</option>'
            //     );
            //   });
          });
      }
    });
  }

  $('.datatables-company tbody').on('click', '.status-update', function () {
    $this = $(this);
    var status = $this.data('value');
    var id = $this.data('id');
    $.ajax({
      url: 'manage-company/update-status',
      type: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: { id: id, status: status },
      success: function (response) {
        if (response.status) {
          dt_user.ajax.reload();
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: response.message,
            showConfirmButton: false,
            timer: 1500,
            customClass: {
              confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: response.message,
            customClass: {
              confirmButton: 'btn btn-primary'
            }
          });
          return;
        }
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseJSON);
      }
    });
  });

  // Delete Record
  $('.datatables-company tbody').on('click', '.delete-record', function () {
    // SWal first confirm then delte with ajax
    $this = $(this);
    Swal.fire({
      title: 'Are you sure?',
      text: 'You will not be able to recover this record!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'No, keep it',
      customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-outline-danger ms-1'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        // Ajax request
        $.ajax({
          url: 'manage-company/delete/' + $this.data('id'),
          type: 'GET',
          success: function (response) {
            if (response.error) {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.error,
                customClass: {
                  confirmButton: 'btn btn-primary'
                }
              });
              return;
            }
            dt_user.ajax.reload();
            // dt_user.row($this.parents('tr')).remove().draw();
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Record has been deleted.',
              customClass: {
                confirmButton: 'btn btn-success'
              }
            });
          },
          error: function (xhr, status, error) {
            console.log(xhr.responseJSON);
          }
        });
      }
    });
  });

  //update record
  $('.datatables-company tbody').on('click', '.update-record', function () {
    $this = $(this);
    $('#recordid').val($this.data('id'));
    $('#recordname').val($this.parents('tr').find('td:eq(1)').text());
    var offcanvasElement = document.getElementById('offcanvasUpdateCompany');
    var offcanvasInstance =
      bootstrap.Offcanvas.getInstance(offcanvasElement) || new bootstrap.Offcanvas(offcanvasElement);
    offcanvasInstance.show();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});

// Validation & Phone mask
(function () {
  const addNewCompanyForm = document.getElementById('addNewCompanyForm');
  const updateCompanyForm = document.getElementById('UpdateCompanyForm');

  // Add New User Form Validation
  FormValidation.formValidation(addNewCompanyForm, {
    fields: {
      name: {
        validators: {
          notEmpty: {
            message: 'Please enter Company name '
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        eleValidClass: '',
        rowSelector: function () {
          return '.mb-3';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  }).on('core.form.valid', function () {
    // Submit the form when valid
    $('#addNewCompanyForm').submit();
  });

  // Update Company Form Validation
  FormValidation.formValidation(updateCompanyForm, {
    fields: {
      name: {
        validators: {
          notEmpty: {
            message: 'Please enter Company name '
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        eleValidClass: '',
        rowSelector: function () {
          return '.mb-3';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  }).on('core.form.valid', function () {
    // Submit the form when valid
    $('#UpdateCompanyForm').submit();
  });
})();
