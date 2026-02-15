'use strict';

$(function () {
  const flatPickrList = [].slice.call(document.querySelectorAll('.flatpickr-validation'));
  // Flat pickr
  if (flatPickrList) {
    flatPickrList.forEach(flatPickr => {
      flatPickr.flatpickr({
        monthSelectorType: 'static'
      });
    });
  }
  $('#addNewTodoForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission
    var formData = new FormData(this);
    $.ajax({
      url: '/manage-todo',
      type: 'POST',
      data: formData,
      success: function (response) {
        dt_todo.ajax.reload();
        var offcanvasElement = document.getElementById('offcanvasAddCompany');
        var offcanvasInstance =
          bootstrap.Offcanvas.getInstance(offcanvasElement) || new bootstrap.Offcanvas(offcanvasElement);
        offcanvasInstance.hide();
        $('#addNewTodoForm').trigger('reset');
        toastr.success(response.message, 'Success', {
          closeButton: true,
          progressBar: true,
          timeOut: 2000,
          positionClass: 'toast-top-right'
        });
        $('.update-company').val(response.todo.company_id).trigger('change');
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
  var dt_todo = $('.datatables-todo').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: '/manage-todo/show?company_id=select',
      type: 'GET',
      data: function (d) {
        d._token = '{{ csrf_token() }}';
        d.company_id = $('.update-company').val();
        d.month = $('#month_filter').val();
      }
    },
    columns: [
      { data: null, defaultContent: '' },

      { data: 'next_visit_todo' },
      { data: 'company' },
      { data: 'toodo_date' },
      { data: 'action', defaultContent: '' }
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
        targets: 1,

        render: function (data, type, full, meta) {
          // example data = 2025-05-09
          var today = new Date();
          var tododate = full['toodo_date'];
          if (typeof tododate === 'string' && !isNaN(Date.parse(tododate))) {
            var parts = tododate.split('-'); // "2025-05-09" → [2025, 05, 09]
            var date = new Date(parts[0], parts[1] - 1, parts[2]); // Note: month is 0-indexed
            if (date.toDateString() === today.toDateString()) {
              //change the color of the parent tr
              return `<span style="color: black;">${data}</span>`;
            } else if (date < today) {
              return `<span style="color: red;">${data}</span>`;
            } else {
              return `<span style="color: blue;">${data}</span>`;
            }
          } else {
            return `<span style="color: red;">${data}</span>`;
          }
        }
      },
      {
        // Plans
        targets: 2,
        responsivePriority: 1,
        orderable: false,
        render: function (data, type, full, meta) {
          var $company = full['company'];
          var today = new Date();
          var tododate = full['toodo_date'];
          if (typeof tododate === 'string' && !isNaN(Date.parse(tododate))) {
            var parts = tododate.split('-'); // "2025-05-09" → [2025, 05, 09]
            var date = new Date(parts[0], parts[1] - 1, parts[2]); // Note: month is 0-indexed
            var style = '';
            if (date.toDateString() === today.toDateString()) {
              //change the color of the parent tr
              style = 'style="color: black;"';
            } else if (date < today) {
              style = 'style="color: red;"';
            } else {
              style = 'style="color: blue;"';
            }
          } else {
            style = 'style="color: red;"';
          }

          return '<span class="fw-medium" ' + style + '>' + ($company ? $company.name : '') + '</span>';
        }
      },
      {
        targets: 3,

        render: function (data, type, full, meta) {
          // example data = 2025-05-09
          var today = new Date();
          if (typeof data === 'string' && !isNaN(Date.parse(data))) {
            var parts = data.split('-'); // "2025-05-09" → [2025, 05, 09]
            var date = new Date(parts[0], parts[1] - 1, parts[2]); // Note: month is 0-indexed

            if (date.toDateString() === today.toDateString()) {
              return `<span style="color: black;">${data}</span>`;
            } else if (date < today) {
              return `<span style="color: red;">${data}</span>`;
            } else {
              return `<span style="color: blue;">${data}</span>`;
            }
          } else {
            return `<span style="color: red;">${data}</span>`;
          }
        }
      },
      {
        targets: -1,
        orderable: false,
        searchable: false,
        render: function (data, type, full) {
          return `
          <a href="javascript:;" class="text-primary me-2"><i class="ti ti-edit"></i></a>
          <a href="javascript:;" class="text-danger delete-record" data-id="${full.id}"><i class="ti ti-trash"></i></a>
          <a href="javascript:;" class="text-warning transfer-record" data-id="${full.id}"><i class="ti ti-file-symlink"></i></a>
        `;
        }
      }
    ],
    dom:
      '<"row me-2"' +
      '<"col-md-2"<"me-3"l>>' +
      '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
      '>r' + // 👈 Add this line to show the processing indicator
      't' +
      '<"row mx-2"' +
      '<"col-sm-12 col-md-6"i>' +
      '<"col-sm-12 col-md-6"p>' +
      '>',

    buttons: [
      // {
      //   extend: 'collection',
      //   className: 'btn btn-label-secondary dropdown-toggle mx-3 waves-effect waves-light',
      //   text: '<i class="ti ti-screen-share me-1 ti-xs"></i>Export',
      //   buttons: [
      //     {
      //       extend: 'csv',
      //       text: '<i class="ti ti-file-text me-2" ></i>Csv',
      //       className: 'dropdown-item',
      //       exportOptions: {
      //         columns: [1, 2, 3, 4, 5],
      //         // prevent avatar to be display
      //         format: {
      //           body: function (inner, coldex, rowdex) {
      //             if (inner.length <= 0) return inner;
      //             var el = $.parseHTML(inner);
      //             var result = '';
      //             $.each(el, function (index, item) {
      //               if (item.classList !== undefined && item.classList.contains('user-name')) {
      //                 result = result + item.lastChild.firstChild.textContent;
      //               } else if (item.innerText === undefined) {
      //                 result = result + item.textContent;
      //               } else result = result + item.innerText;
      //             });
      //             return result;
      //           }
      //         }
      //       }
      //     },
      //     {
      //       extend: 'excel',
      //       text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
      //       className: 'dropdown-item',
      //       exportOptions: {
      //         columns: [1, 2, 3, 4, 5],
      //         // prevent avatar to be display
      //         format: {
      //           body: function (inner, coldex, rowdex) {
      //             if (inner.length <= 0) return inner;
      //             var el = $.parseHTML(inner);
      //             var result = '';
      //             $.each(el, function (index, item) {
      //               if (item.classList !== undefined && item.classList.contains('user-name')) {
      //                 result = result + item.lastChild.firstChild.textContent;
      //               } else if (item.innerText === undefined) {
      //                 result = result + item.textContent;
      //               } else result = result + item.innerText;
      //             });
      //             return result;
      //           }
      //         }
      //       }
      //     },

      //     {
      //       extend: 'copy',
      //       text: '<i class="ti ti-copy me-2" ></i>Copy',
      //       className: 'dropdown-item',
      //       exportOptions: {
      //         columns: [1, 2, 3, 4, 5],
      //         // prevent avatar to be display
      //         format: {
      //           body: function (inner, coldex, rowdex) {
      //             if (inner.length <= 0) return inner;
      //             var el = $.parseHTML(inner);
      //             var result = '';
      //             $.each(el, function (index, item) {
      //               if (item.classList !== undefined && item.classList.contains('user-name')) {
      //                 result = result + item.lastChild.firstChild.textContent;
      //               } else if (item.innerText === undefined) {
      //                 result = result + item.textContent;
      //               } else result = result + item.innerText;
      //             });
      //             return result;
      //           }
      //         }
      //       }
      //     }
      //   ]
      // },

      {
        text: '<i class="ti ti-printer me-0 me-sm-1 ti-xs "></i><span class="d-none d-sm-inline-block">Print</span>',
        className: 'print-todo btn btn-secondary waves-effect waves-light ms-2 d-none'
      },
      {
        text: '<i class="ti ti-trash me-0 me-sm-1 ti-xs "></i><span class="d-none d-sm-inline-block">Delete</span>',
        className: 'delete-todo-all btn btn-danger waves-effect waves-light mx-2'
      },
      {
        text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add New</span>',
        className: 'add-new btn btn-primary waves-effect waves-light',
        attr: {
          'data-bs-toggle': 'offcanvas',
          'data-bs-target': '#offcanvasAddCompany'
        }
      }
    ],

    initComplete: function () {
      this.api()
        .columns(2)
        .every(function () {
          var column = this;
          var select = $('.update-company').on('change', function () {
            $('#month_filter').val('');
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
          select.append('<option value="all">All Companies</option>');
        });

      this.api()
        .columns(3)
        .every(function () {
          var column = this;
          var select = $('#month_filter').on('change', function () {
            $('.update-company').val('');
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            column.search(val ? val : '', true, false).draw();
          });
        });
      // Adding status filter once table initialized

      //add butto to reset the filter

      // Add a button to reset the filter
      var resetButton = $('<button class="btn btn-warning ms-2">Reset Filters</button>')
        .appendTo('.todo_filter')
        .on('click', function () {
          // Reset the select elements
          $('#month_filter').val('').trigger('change');
          $('.update-company').val('').trigger('change');

          // Redraw the DataTable
          // dt_user.ajax.reload();
        });
    },

    order: [[3, 'asc']],
    orderable: false,
    responsive: true,
    paging: false
  });

  $('.datatables-todo tbody').on('click', 'a.text-primary', function () {
    $('a.text-primary').addClass('d-none');
    var row = dt_todo.row($(this).parents('tr'));
    var rowData = row.data();

    // Convert cells into editable inputs
    var $rowNode = $(row.node());

    var ind = 0;
    if ($('.update-company').val() != 'all') {
      ind = 1;
    } else {
      $rowNode
        .find('td')
        .eq(2)
        .html(`<input type="text" class="form-control" value="${rowData.company.name}" disabled />`);
    }

    $rowNode.find('td').eq(1).html(`<textarea type="text" class="form-control">${rowData.next_visit_todo}</textarea>`);

    $rowNode
      .find('td')
      .eq(3 - ind)
      .html(`<input type="date"  id="flatpickr-validation" class="form-control" value="${rowData.toodo_date}" />`);
    $('#flatpickr-validation').flatpickr({
      monthSelectorType: 'static'
    });
    // Replace action buttons with Save/Cancel
    $rowNode.find('td').eq(4 - ind).html(`
      <a href="javascript:;" class="text-success save-edit" data-id="${rowData.id}"><i class="ti ti-check"></i></a>
      <a href="javascript:;" class="text-secondary cancel-edit"><i class="ti ti-x"></i></a>
    `);
  });

  // Save changes
  $('.datatables-todo tbody').on('click', 'a.save-edit', function () {
    // wait 2sec

    var $row = $(this).closest('tr');
    var row = dt_todo.row($row);
    var id = $(this).data('id');
    var ind = 0;
    if ($('.update-company').val() != 'all') {
      ind = 1;
    }
    var updatedData = {
      id: id,
      next_visit_todo: $row.find('td').eq(1).find('textarea').val(),
      toodo_date: $row
        .find('td')
        .eq(3 - ind)
        .find('input')
        .val()
    };

    // Send AJAX to update on the server
    $.ajax({
      url: `/manage-todo`, // You need to create this route in backend
      type: 'POST',
      data: {
        ...updatedData,
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {
        if (response.status) {
          // toastr.success(response.message, 'Success', {
          //   closeButton: true,
          //   progressBar: true,
          //   timeOut: 2000,
          //   positionClass: 'toast-top-right'
          // });
        } else {
          toastr.error(response.message, 'Error', {
            closeButton: true,
            progressBar: true,
            timeOut: 2000,
            positionClass: 'toast-top-right'
          });
        }
        dt_todo.ajax.reload(null, false); // Reload only this page
      },
      error: function (xhr) {
        toastr.error('Error updating todo', 'Error', {
          closeButton: true,
          progressBar: true,
          timeOut: 2000,
          positionClass: 'toast-top-right'
        });
      }
    });
  });

  // Cancel editing
  $('.datatables-todo tbody').on('click', 'a.cancel-edit', function () {
    dt_todo.ajax.reload(null, false); // Just reload to revert edits
  });

  // delete

  $('.datatables-todo tbody').on('click', 'a.delete-record', function () {
    var id = $(this).data('id');
    var row = dt_todo.row($(this).parents('tr'));
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'No, cancel!'
    }).then(result => {
      if (result.isConfirmed) {
        $.ajax({
          url: `/manage-todo/${id}`,
          type: 'DELETE',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            if (response.status) {
              // toastr.success(response.message, 'Success', {
              //   closeButton: true,
              //   progressBar: true,
              //   timeOut: 2000,
              //   positionClass: 'toast-top-right'
              // });
              dt_todo.row(row).remove().draw();
            } else {
              toastr.error(response.message, 'Error', {
                closeButton: true,
                progressBar: true,
                timeOut: 2000,
                positionClass: 'toast-top-right'
              });
            }
          },
          error: function (xhr) {
            toastr.error('Error deleting todo', 'Error', {
              closeButton: true,
              progressBar: true,
              timeOut: 2000,
              positionClass: 'toast-top-right'
            });
          }
        });
      }
    });
  });
  $('.datatables-todo tbody').on('click', 'a.transfer-record', function () {
    var id = $(this).data('id');
    var row = dt_todo.row($(this).parents('tr'));

    $.ajax({
      url: `/manage-todo/transfer/${id}`,
      type: 'GET',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {
        if (response.status) {
          toastr.success(response.message, 'Success', {
            closeButton: true,
            progressBar: true,
            timeOut: 2000,
            positionClass: 'toast-top-right'
          });
          dt_todo.row(row).remove().draw();
        } else {
          toastr.error(response.message, 'Error', {
            closeButton: true,
            progressBar: true,
            timeOut: 2000,
            positionClass: 'toast-top-right'
          });
        }
      },
      error: function (xhr) {
        toastr.error('Error while trnasfering todo', 'Error', {
          closeButton: true,
          progressBar: true,
          timeOut: 2000,
          positionClass: 'toast-top-right'
        });
      }
    });
  });
  $('.update-company').on('change', function () {
    if ($('.update-company').val() != 'all') {
      //hide 2 column of the table
      dt_todo.column(2).visible(false);
    } else {
      //show 2 column of the table
      dt_todo.column(2).visible(true);
    }
    if ($(this).val() == '' || $(this).val() == 'all') {
      $('.print-todo').addClass('d-none');
    } else {
      $('.print-todo').removeClass('d-none');
    }
  });
  $('.print-todo').on('click', function () {
    var company_id = $('.update-company').val();
    var url = '/manage-todo/print/' + company_id;
    window.open(url, 'Print', 'width=650,height=650');
  });
});
(function () {
  const addNewTodoForm = document.getElementById('addNewTodoForm');

  // Add New User Form Validation
  const fv = FormValidation.formValidation(addNewTodoForm, {
    fields: {
      company_id: {
        validators: {
          notEmpty: {
            message: 'Please select a company'
          }
        }
      },
      next_visit_todo: {
        validators: {
          notEmpty: {
            message: 'Please enter a todo'
          }
        }
      },
      toodo_date: {
        validators: {
          notEmpty: {
            message: 'Please select a date'
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
  }).on('core.form.valid', function () {
    // Submit the form when valid
    $('#addNewTodoForm').submit();
  });
})();
