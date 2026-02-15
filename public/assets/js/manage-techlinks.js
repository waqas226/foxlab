/**
 * Page User List
 */

'use strict';

$(function () {
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
  var dt_user_table = $('.datatables-techlinks'),
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
        url: 'manage-techlinks/show',
        type: 'GET',
        data: function (d) {
          console.log(d);
          d._token = '{{ csrf_token() }}';
        }
      },
      // assetsPath + 'json/user-list.json', // JSON file to add data
      columns: [
        // columns according to JSON
        { data: '' },

        { data: 'description' },
        { data: 'link' },
        { data: 'date_added' },
        { data: 'file' },
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
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $link = full['link'];

            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center user-name">' +
              '<a href="' +
              $link +
              '" target="_blank" >' +
              $link +
              '</a>' +
              '</div>';
            return $row_output;
          }
        },

        {
          // User Status
          targets: 3,
          render: function (data, type, full, meta) {
            // var $date = '11 Jan 2025';
            var $date = full['created_at'];

            return moment($date).format('Y-M-D');
          }
        },
        {
          // User Status
          targets: 4,
          render: function (data, type, full, meta) {
            var $file = full['image'];
            var url = '/uploads/' + $file;
            var row_output = '';
            if ($file) {
              var row_output =
                '<div class="d-flex justify-content-start align-items-center user-name">' +
                '<a href="' +
                url +
                '" target="download" class="text-truncate">' +
                '<i class="ti ti-file-download"></i>' +
                '</a>' +
                '</div>';
            }
            return row_output;
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
              '<a href="/manage-techlinks/' +
              full['id'] +
              '/edit" class="text-body update-record"><i class="ti ti-edit ti-sm me-2"></i></a>' +
              '<a href="javascript:;" class="text-body delete-record" data-id="' +
              full['id'] +
              '" ><i class="ti ti-trash ti-sm mx-2"></i></a>' +
              '</div>'
            );
          }
        }
      ],
      order: [[3, 'desc']],
      pageLength: 50,
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
          text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add Tech Link</span>',
          className: 'add-new btn btn-primary waves-effect waves-light',
          attr: {
            onclick: "window.location.href='manage-techlinks/create'"
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['description'];
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
      }
    });
  }

  // Delete Record
  $('.datatables-techlinks tbody').on('click', '.delete-record', function () {
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
          url: 'manage-techlinks/delete/' + $this.data('id'),
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

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});
