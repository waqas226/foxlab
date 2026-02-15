/**
 * Page User List
 */

'use strict';

$(function () {
  var userTable = $('.datatables-users1').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: 'show',
      type: 'GET',
      data: function (d) {
        console.log(d);
        d._token = '{{ csrf_token() }}';
        d.user_role = $('.user_role').val();
        d.user_plan = $('.user_plan').val();
        d.user_status = $('.user_status').val();
      }
    },
    columns: [
      // columns according to JSON
      { data: '' },
      { data: 'id' },
      { data: 'username' },

      { data: 'email' },

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
        targets: 2,
        responsivePriority: 4,
        render: function (data, type, full, meta) {
          var $name = full['username'],
            $email = full['email'];

          if (false) {
            // For Avatar image
            var $output =
              '<img src="' + assetsPath + 'img/avatars/' + $image + '" alt="Avatar" class="rounded-circle">';
          } else {
            // For Avatar badge
            var stateNum = Math.floor(Math.random() * 6);
            var states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
            var $state = states[stateNum],
              $name = full['username'],
              $initials = $name.match(/\b\w/g) || [];
            $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
            $output = ''; //'<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
          }
          // Creates full output for row
          var $row_output =
            '<div class="d-flex justify-content-start align-items-center user-name">' +
            // '<div class="avatar-wrapper">' +
            // '<div class="avatar me-3">' +
            // $output +
            // '</div>' +
            // '</div>' +
            '<div class="d-flex flex-column">' +
            '<a href="' +
            userView +
            '" class="text-body text-truncate"><span class="fw-medium">' +
            $name +
            '</span></a>' +
            '<small class="text-muted">' +
            $email +
            '</small>' +
            '</div>' +
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
        // Plans
        targets: 3,
        render: function (data, type, full, meta) {
          var $plan = full['email'];

          return '<span class="fw-medium">' + $plan + '</span>';
        }
      },
      {
        // User Status
        targets: 4,
        render: function (data, type, full, meta) {
          var $date = '11 Jan 2025 <br> 10:45:17 AM';

          return $date;
        }
      },
      {
        // User Status
        targets: 5,
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
          return (
            '<div class="d-flex align-items-center">' +
            '<a href="javascript:;" class="text-body"><i class="ti ti-edit ti-sm me-2"></i></a>' +
            '<a href="javascript:;" class="text-body delete-record"><i class="ti ti-trash ti-sm mx-2"></i></a>' +
            '<a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>' +
            '<div class="dropdown-menu dropdown-menu-end m-0">' +
            '<a href="' +
            userView +
            '" class="dropdown-item">View</a>' +
            '<a href="javascript:;" class="dropdown-item">Suspend</a>' +
            '</div>' +
            '</div>'
          );
        }
      }
    ],
    order: [[1, 'asc']],
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
              columns: [1, 2, 3, 4, 5],
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
            extend: 'pdf',
            text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
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
        text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add New User</span>',
        className: 'add-new btn btn-primary waves-effect waves-light',
        attr: {
          'data-bs-toggle': 'offcanvas',
          'data-bs-target': '#offcanvasAddUser'
        }
      }
    ],
    responsive: {
      details: {
        display: $.fn.dataTable.Responsive.display.modal({
          header: function (row) {
            var data = row.data();
            return 'Details of ' + data['username'];
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
      this.api()
        .columns(3)
        .every(function () {
          var column = this;
          var select = $(
            '<select id="UserPlan" class="form-select text-capitalize"><option value=""> Select Company </option></select>'
          )
            .appendTo('.user_plan')
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
      // Adding status filter once table initialized
      this.api()
        .columns(7)
        .every(function () {
          var column = this;
          var select = $(
            '<select id="FilterTransaction" class="form-select text-capitalize"><option value=""> Select Status </option></select>'
          )
            .appendTo('.user_status')
            .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());
              column.search(val ? '^' + val + '$' : '', true, false).draw();
            });

          column
            .data()
            .unique()
            .sort()
            .each(function (d, j) {
              select.append(
                '<option value="' + statusObj[d].title + '" class="text-capitalize">' + statusObj[d].title + '</option>'
              );
            });
        });
    }
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
      url: '/manage-users/show',
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

      { data: 'username' },

      { data: 'email' },
      { data: 'role' },

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
        responsivePriority: 4,
        render: function (data, type, full, meta) {
          var $name = full['username'],
            $email = full['email'],
            $image = full['avatar'];
          if ($image && false) {
            // For Avatar image
            var $output =
              '<img src="' + assetsPath + 'img/avatars/' + $image + '" alt="Avatar" class="rounded-circle">';
          } else {
            // For Avatar badge
            var stateNum = Math.floor(Math.random() * 6);
            var states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
            var $state = states[stateNum],
              $username = full['username'],
              $name = full['firstname'] + ' ' + full['lastname'],
              $initials = $name.match(/\b\w/g) || [];
            $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
            $output = ''; //'<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
          }
          // Creates full output for row
          var $row_output =
            '<div class="d-flex justify-content-start align-items-center user-name">' +
            // '<div class="avatar-wrapper">' +
            // '<div class="avatar me-3">' +
            // $output +
            // '</div>' +
            // '</div>' +
            '<div class="d-flex flex-column">' +
            '<a href="' +
            userView +
            full['id'] +
            '/edit' +
            '" class="text-body text-truncate"><span class="fw-medium">' +
            $name +
            '</span></a>' +
            '<small class="text-muted">' +
            $username +
            '</small>' +
            '</div>' +
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
        // Plans
        targets: 2,
        render: function (data, type, full, meta) {
          var $plan = full['email'];

          return '<span class="fw-medium">' + $plan + '</span>';
        }
      },
      {
        // Plans
        targets: 3,
        render: function (data, type, full, meta) {
          var $role = full['role'];

          return '<span class="fw-medium">' + $role.name + '</span>';
        }
      },
      //extra mail

      {
        // User Status
        targets: 4,
        render: function (data, type, full, meta) {
          var $date = full['created_at'];

          return moment($date).format('YYYY-MM-DD hh:mm:ss A');
        }
      },
      {
        // User Status
        targets: 5,
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
            '<a href="/manage-users/' +
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
        text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add New User</span>',
        className: 'add-new btn btn-primary waves-effect waves-light',
        attr: {
          onclick: 'window.location.href = "manage-users/add"'
        }
      }
    ],
    // For responsive popup
    responsive: {
      details: {
        display: $.fn.dataTable.Responsive.display.modal({
          header: function (row) {
            var data = row.data();
            return 'Details of ' + data['username'];
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
      this.api()
        .columns(2)
        .every(function () {
          var column = this;
          var select = $('#UserPlan0000').on('change', function () {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            column.search(val ? '^' + val + '$' : '', true, false).draw();
          });

          // const uniqueNames = new Set();
          // column
          //   .data()
          //   .unique()
          //   .sort()
          //   .each(function (d) {
          //     if (d && d.name && !uniqueNames.has(d.name)) {
          //       uniqueNames.add(d.name);
          //       select.append('<option value="' + d.name + '">' + d.name + '</option>');
          //     }
          //   });
        });
      // Adding status filter once table initialized
      this.api()
        .columns(5)
        .every(function () {
          var column = this;
          var select = $(
            '<select id="FilterTransaction" class="form-select text-capitalize user-role"><option value=""> Select Status </option></select>'
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
              $('.update-company').val('').trigger('change');
              $('.user-role').val('').trigger('change');

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
          url: '/manage-users/' + id,
          type: 'DELETE',
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
      url: '/manage-users/update-status/' + id,
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
})();
