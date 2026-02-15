'use strict';

$(function () {
  if ($('#task_id').val()) {
    var datatable_todo = $('.todo-table').DataTable({
      searching: false,
      paging: false,
      info: false,
      responsive: false,
      ajax: {
        url: '/manage-todo/show/' + $('#task_id').val(),
        type: 'GET'
      },
      columns: [{ data: 'toodo_date' }, { data: 'next_visit_todo' }, { data: 'action' }],

      columnDefs: [
        {
          targets: 0,

          render: function (data, type, full, meta) {
            // example data = 2025-05-09
            var today = new Date();

            var parts = data.split('-'); // "2025-05-09" → [2025, 05, 09]
            var date = new Date(parts[0], parts[1] - 1, parts[2]); // Note: month is 0-indexed
            if (date.toDateString() === today.toDateString()) {
              //change the color of the parent tr
              return `<span style="color: black;">${data}</span>`;
            } else if (date < today) {
              return `<span style="color: red;">${data}</span>`;
            } else {
              return `<span style="color: blue;">${data}</span>`;
            }
          }
        },
        {
          targets: 1,

          render: function (data, type, full, meta) {
            // example data = 2025-05-09
            var today = new Date();
            var tododate = full['toodo_date'];
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
          }
        },
        {
          targets: -1,
          orderable: false,
          className: 'text-center',
          render: function (data, type, full, meta) {
            return `
          <a href="javascript:;" class="text-primary me-2"><i class="ti ti-edit"></i></a>
          <a href="javascript:;" class="text-danger delete-record" data-id="${full.id}"><i class="ti ti-trash"></i></a>
        `;
          }
        }
      ]
      // asset
    });

    $('.todo-table tbody').on('click', 'a.text-primary', function () {
      $('a.text-primary').addClass('d-none');
      var row = datatable_todo.row($(this).parents('tr'));
      var rowData = row.data();

      // Convert cells into editable inputs
      var $rowNode = $(row.node());

      $rowNode
        .find('td')
        .eq(1)
        .html(`<textarea type="text" class="form-control">${rowData.next_visit_todo}</textarea>`);

      $rowNode
        .find('td')
        .eq(0)
        .html(`<input type="date" class="form-control" id="flatpickr-validation" value="${rowData.toodo_date}" />`);
      $('#flatpickr-validation').flatpickr({
        monthSelectorType: 'static'
      });
      // Replace action buttons with Save/Cancel
      $rowNode.find('td').eq(2).html(`
      <a href="javascript:;" class="text-success save-edit" data-id="${rowData.id}"><i class="ti ti-check"></i></a>
      <a href="javascript:;" class="text-secondary cancel-edit"><i class="ti ti-x"></i></a>
    `);
    });
    $('.todo-table tbody').on('click', 'a.save-edit', function () {
      var $row = $(this).closest('tr');
      var row = datatable_todo.row($row);
      var id = $(this).data('id');

      console.log($row);
      var updatedData = {
        id: id,
        next_visit_todo: $row.find('td').eq(1).find('textarea').val(),
        toodo_date: $row.find('td').eq(0).find('input').val()
      };
      $('a.text-primary').removeClass('d-none');
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
            toastr.success(response.message, 'Success', {
              closeButton: true,
              progressBar: true,
              timeOut: 2000,
              positionClass: 'toast-top-right'
            });
          } else {
            toastr.error(response.message, 'Error', {
              closeButton: true,
              progressBar: true,
              timeOut: 2000,
              positionClass: 'toast-top-right'
            });
          }
          datatable_todo.ajax.reload(null, false); // Reload only this page
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
    $('.todo-table tbody').on('click', 'a.cancel-edit', function () {
      datatable_todo.ajax.reload(null, false); // Just reload to revert edits
      $('a.text-primary').removeClass('d-none');
    });

    // delete

    $('.todo-table tbody').on('click', 'a.delete-record', function () {
      var id = $(this).data('id');
      var row = datatable_todo.row($(this).parents('tr'));

      $.ajax({
        url: `/manage-todo/${id}`,
        type: 'DELETE',
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
            datatable_todo.row(row).remove().draw();
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
    });
    //renewal table
    var datatable_renewal = $('.renewal-table').DataTable({
      searching: false,
      paging: false,
      info: false,
      responsive: false,
      ajax: {
        url: '/manage-renewals/show/' + $('#task_id').val(),
        type: 'GET'
      },
      columns: [{ data: 'renewal_date' }, { data: 'title' }, { data: 'action' }],
      columnDefs: [
        {
          targets: 0,

          render: function (data, type, full, meta) {
            // example data = 2025-05-09
            var today = new Date();

            var parts = data.split('-'); // "2025-05-09" → [2025, 05, 09]
            var date = new Date(parts[0], parts[1] - 1, parts[2]); // Note: month is 0-indexed
            if (date.toDateString() === today.toDateString()) {
              return `<span style="color: black;">${data}</span>`;
            } else if (date < today) {
              return `<span style="color: red;">${data}</span>`;
            } else {
              return `<span style="color: blue;">${data}</span>`;
            }
          }
        },
        {
          targets: 1,

          render: function (data, type, full, meta) {
            // example data = 2025-05-09
            var today = new Date();
            var tododate = full['renewal_date'];
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
          }
        },
        {
          targets: -1,
          orderable: false,
          className: 'text-center',
          render: function (data, type, full, meta) {
            return `
        <a href="javascript:;" class="text-primary me-2"><i class="ti ti-edit"></i></a>
        <a href="javascript:;" class="text-danger delete-record" data-id="${full.id}"><i class="ti ti-trash"></i></a>
      `;
          }
        }
      ]
      // asset
    });

    $('.renewal-table tbody').on('click', 'a.text-primary', function () {
      $('a.text-primary').addClass('d-none');
      var row = datatable_renewal.row($(this).parents('tr'));
      var rowData = row.data();

      // Convert cells into editable inputs
      var $rowNode = $(row.node());

      $rowNode.find('td').eq(1).html(`<textarea type="text" class="form-control">${rowData.title}</textarea>`);

      $rowNode
        .find('td')
        .eq(0)
        .html(`<input type="date" class="form-control" id="flatpickr-validation" value="${rowData.renewal_date}" />`);
      $('#flatpickr-validation').flatpickr({
        monthSelectorType: 'static'
      });
      // Replace action buttons with Save/Cancel
      $rowNode.find('td').eq(2).html(`
    <a href="javascript:;" class="text-success save-edit" data-id="${rowData.id}"><i class="ti ti-check"></i></a>
    <a href="javascript:;" class="text-secondary cancel-edit"><i class="ti ti-x"></i></a>
  `);
    });
    $('.renewal-table tbody').on('click', 'a.save-edit', function () {
      var $row = $(this).closest('tr');
      var row = datatable_renewal.row($row);
      var id = $(this).data('id');

      var updatedData = {
        id: id,
        title: $row.find('td').eq(1).find('textarea').val(),
        renewal_date: $row.find('td').eq(0).find('input').val()
      };

      // Send AJAX to update on the server
      $.ajax({
        url: `/manage-renewals`, // You need to create this route in backend
        type: 'POST',
        data: {
          ...updatedData,
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
          } else {
            toastr.error(response.message, 'Error', {
              closeButton: true,
              progressBar: true,
              timeOut: 2000,
              positionClass: 'toast-top-right'
            });
          }
          datatable_renewal.ajax.reload(null, false);
          $('a.text-primary').removeClass('d-none'); // Reload only this page
        },
        error: function (xhr) {
          toastr.error('Error updating renewal', 'Error', {
            closeButton: true,
            progressBar: true,
            timeOut: 2000,
            positionClass: 'toast-top-right'
          });
        }
      });
    });

    // Cancel editing
    $('.renewal-table tbody').on('click', 'a.cancel-edit', function () {
      datatable_renewal.ajax.reload(null, false); // Just reload to revert edits
      $('a.text-primary').removeClass('d-none');
    });

    // delete

    $('.renewal-table tbody').on('click', 'a.delete-record', function () {
      var id = $(this).data('id');
      var row = datatable_renewal.row($(this).parents('tr'));

      $.ajax({
        url: `/manage-renewals/${id}`,
        type: 'DELETE',
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
            datatable_renewal.row(row).remove().draw();
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
          toastr.error('Error deleting renewal', 'Error', {
            closeButton: true,
            progressBar: true,
            timeOut: 2000,
            positionClass: 'toast-top-right'
          });
        }
      });
    });
    //end renewal
  }
  //add todo
  $('.submit-todo').on('click', function () {
    if ($('#todo').val() == '') {
      toastr.error('Please enter a todo', 'Error', {
        closeButton: true,
        progressBar: true,
        timeOut: 2000,
        positionClass: 'toast-top-right'
      });
      return;
    }
    if ($('#company_id').val() == '') {
      toastr.error('Please select a company', 'Error', {
        closeButton: true,
        progressBar: true,
        timeOut: 2000,
        positionClass: 'toast-top-right'
      });
      return;
    }

    var updatedData = {
      next_visit_todo: $('#todo').val(),
      toodo_date: $('#task-date').val(),
      company_id: $('#company_id').val()
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
          toastr.success(response.message, 'Success', {
            closeButton: true,
            progressBar: true,
            timeOut: 2000,
            positionClass: 'toast-top-right'
          });
          $('#todo').val('');
        } else {
          toastr.error(response.message, 'Error', {
            closeButton: true,
            progressBar: true,
            timeOut: 2000,
            positionClass: 'toast-top-right'
          });
        }
        datatable_todo.ajax.reload(null, false); // Reload only this page
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

  $('.submit-renewal').on('click', function () {
    if ($('#renewal').val() == '') {
      toastr.error('Please enter a renewal', 'Error', {
        closeButton: true,
        progressBar: true,
        timeOut: 2000,
        positionClass: 'toast-top-right'
      });
      return;
    }
    if ($('#company_id').val() == '') {
      toastr.error('Please select a company', 'Error', {
        closeButton: true,
        progressBar: true,
        timeOut: 2000,
        positionClass: 'toast-top-right'
      });
      return;
    }
    var updatedData = {
      title: $('#renewal').val(),
      renewal_date: $('#task-date').val(),
      company_id: $('#company_id').val()
    };

    // Send AJAX to update on the server
    $.ajax({
      url: `/manage-renewals`, // You need to create this route in backend
      type: 'POST',
      data: {
        ...updatedData,
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
          $('#renewal').val('');
        } else {
          toastr.error(response.message, 'Error', {
            closeButton: true,
            progressBar: true,
            timeOut: 2000,
            positionClass: 'toast-top-right'
          });
        }
        datatable_renewal.ajax.reload(null, false); // Reload only this page
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

  $('#todolist_toggle').on('click', function () {
    const todolistDiv = $('#todolist_div');
    if (todolistDiv.hasClass('d-none')) {
      todolistDiv.removeClass('d-none');
      $(this).addClass('active');
    } else {
      todolistDiv.addClass('d-none');
      $(this).removeClass('active');
    }
  });

  $('#printid11').on('click', function () {
    // onclick="window.open('popup-task-detail.php?task_id=2335','Print','width=650,height=650');"
    var task_id = $('#task_id').val();
    const todolistDiv = $('#todolist_div');
    var url = '/manage-tasks/print/' + task_id + '?todo=print';
    if (todolistDiv.hasClass('d-none')) {
      url = '/manage-tasks/print/' + task_id;
    }

    var newWindow = window.open(url, 'Print', 'width=650,height=650');
    newWindow.onload = function () {
      newWindow.print();
    };
  });

  $('#printhardware').on('click', function () {
    // onclick="window.open('/manage-devices/print/{{$task->company_id}}','Print','width=650,height=650');"
    var company_id = $('#company_id').val();
    var url = '/manage-devices/print/' + company_id;
    var newWindow = window.open(url, 'Print', 'width=650,height=650');
    newWindow.onload = function () {
      newWindow.print();
    };
  });
  const flatPickrList = [].slice.call(document.querySelectorAll('.flatpickr-validation'));
  // Flat pickr
  if (flatPickrList) {
    flatPickrList.forEach(flatPickr => {
      flatPickr.flatpickr({
        monthSelectorType: 'static'
      });
    });
  }

  var dt_todo_table = $('.datatables');
  var dt_todo = dt_todo_table.DataTable({
    searching: false,
    paging: false,
    info: false,
    columnDefs: [
      {
        targets: -1,
        orderable: false,
        className: 'text-center'
      }
    ]
  });

  const fullToolbar = [
    [
      {
        font: []
      },
      {
        size: []
      }
    ],
    ['bold', 'italic', 'underline', 'strike'],
    [
      {
        color: []
      },
      {
        background: []
      }
    ],
    [
      {
        script: 'super'
      },
      {
        script: 'sub'
      }
    ],
    [
      {
        header: '1'
      },
      {
        header: '2'
      },
      'blockquote',
      'code-block'
    ],
    [
      {
        list: 'ordered'
      },
      {
        list: 'bullet'
      },
      {
        indent: '-1'
      },
      {
        indent: '+1'
      }
    ],
    [{ direction: 'rtl' }],
    ['link', 'image', 'video', 'formula'],
    ['clean']
  ];
  const fullEditor = new Quill('#full-editor', {
    bounds: '#full-editor',
    placeholder: 'Type Something...',
    modules: {
      formula: true,
      toolbar: fullToolbar
    },
    theme: 'snow'
  });

  // Auto-save on every key enter
  fullEditor.on('text-change', function () {
    const editorContent = fullEditor.root.innerHTML;
    //_token
    const token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      url: 'save-long-desc',
      type: 'POST',
      data: { long_desc: editorContent, _token: token },
      success: function (response) {
        console.log('Content auto-saved successfully.');
      },
      error: function () {
        console.error('Failed to auto-save content.');
      }
    });
  });
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
  const bsValidationForms = document.querySelectorAll('.needs-validation');

  // Loop over them and prevent submission
  Array.prototype.slice.call(bsValidationForms).forEach(function (form) {
    form.addEventListener(
      'submit',
      function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
          form.classList.add('was-validated');
        } else {
          // Prevent default form submission for AJAX handling
          event.preventDefault();
          var formData = new FormData(form);

          $.ajax({
            url: '/manage-tasks',
            type: 'POST',
            data: formData,
            success: function (response) {
              if (response.status) {
                form.reset();
                form.classList.remove('was-validated');

                toastr.success(response.message, 'Success', {
                  closeButton: true,
                  progressBar: true,
                  timeOut: 2000,
                  positionClass: 'toast-top-right'
                });

                if (response.id && response.type == 'create') {
                  setTimeout(function () {
                    window.location.href = '/manage-tasks/' + response.id + '/edit';
                  }, 2500);
                } else {
                  window.location.href = '/manage-tasks';
                }
              } else {
                // Show SweetAlert error message
                toastr.error(response.message, {
                  closeButton: true,
                  progressBar: true,
                  timeOut: 2500,
                  positionClass: 'toast-top-right'
                });
              }
            },
            cache: false,
            contentType: false,
            processData: false,
            error: function () {
              // Show SweetAlert error message
              toastr.error('Something went wrong. Please try again.', {
                closeButton: true,
                progressBar: true,
                timeOut: 1500,
                positionClass: 'toast-top-right'
              });
            }
          });
        }
      },
      false
    );
  });
  //todolist_toggle display/hide todolist_div
});
