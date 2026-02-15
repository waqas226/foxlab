/**
 * Page User List
 */

'use strict';

$(function () {
  // Filter event handler

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
  var dt_user_table = $('.datatables-notes'),
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
        url: 'manage-notes/show',
        type: 'GET',
        data: function (d) {
          console.log(d);
          d._token = '{{ csrf_token() }}';
          d.user_status = $('.user_status').val();
        }
      },
      // assetsPath + 'json/user-list.json', // JSON file to add data
      columns: [
        // columns according to JSON
        { data: '' },

        { data: 'title' },
        { data: 'date_added' },
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
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $title = full['title'];

            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center user-name">' +
              // '<div class="avatar-wrapper">' +
              // '<div class="avatar me-3">' +
              // $output +
              // '</div>' +
              // '</div>' +
              $title +
              '</div>';
            return $row_output;
          }
        },

        {
          // User Status
          targets: 2,
          render: function (data, type, full, meta) {
            var $date = full['created_at'];

            //m/d/y
            return moment($date).format('YYYY-MM-DD');
          }
        },

        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-flex align-items-center">' +
              '<a href="javascript:;"  data-id="' +
              full['id'] +
              '" class="text-body print-record"><i class="ti ti-printer ti-sm me-2"></i></a>' +
              '<a href="/manage-notes/' +
              full['id'] +
              '/edit" class="text-body update-record"><i class="ti ti-edit ti-sm me-2"></i></a>' +
              '<a href="javascript:;" class="text-body delete-record" data-id="' +
              full['id'] +
              '" ><i class="ti ti-trash ti-sm mx-2"></i></a>' +
              '<a href="javascript:;" class="text-body open-record"><i class="ti ti-list ti-sm mx-2"></i></a>' +
              '</div>'
            );
          }
        }
      ],
      order: [[2, 'desc']],
      paging: false,
      dom:
        '<"row me-2"' +
        '<"col-md-2"<"me-3"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
        '>t' +
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
              extend: 'print',
              text: '<i class="ti ti-printer me-2" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3],
                // prevent avatar to be print
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
              },
              customize: function (win) {
                //customize print view for dark
                $(win.document.body)
                  .css('color', headingColor)
                  .css('border-color', borderColor)
                  .css('background-color', bodyBg);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              text: '<i class="ti ti-file-text me-2" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3],
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
                columns: [1, 2, 3],
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
              extend: 'pdf',
              text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3],
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
                columns: [1, 2, 3],
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
          text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add New Notes</span>',
          className: 'add-new btn btn-primary waves-effect waves-light',
          attr: {
            onclick: 'window.location.href="manage-notes/create"'
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['title'];
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
      }
    });
  }

  // Show/hide description row
  $('.datatables-notes tbody').on('click', '.open-record', function () {
    var tr = $(this).closest('tr');
    var row = dt_user.row(tr);

    if (row.child.isShown()) {
      row.child.hide();
      tr.removeClass('shown');
    } else {
      var descriptionHtml = row.data().items || '';
      var $descDiv = $('<div class="p-3 bg-light border rounded"></div>');
      $descDiv.html(descriptionHtml); // Safely inject HTML

      row.child($descDiv).show();
      tr.addClass('shown');
    }
  });

  // Delete Record
  $('.datatables-notes tbody').on('click', '.delete-record', function () {
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
          url: 'manage-notes/delete/' + $this.data('id'),
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

  // Print Record
  $('.datatables-notes tbody').on('click', '.print-record', function () {
    $this = $(this);
    var id = $this.data('id');
    var url = '/manage-notes/print/' + id;
    var newWindow = window.open(url, 'Print', 'width=650,height=650');
    newWindow.onload = function () {
      newWindow.print();
    };
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
