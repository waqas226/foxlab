'use strict';

$(function () {
  // Variable declaration for table
  var dt_user_table = $('.datatables-work');

  // Users datatable
  // if (dt_user_table.length)
  var dt_user = dt_user_table.DataTable({
    processing: true,
    serverSide: true,

    ajax: {
      url: '/manage-work-orders/show',
      type: 'GET',
      data: function (d) {
        // Add any additional parameters to the request here
        d.status = $('#FilterTransaction').val() || 'Open'; // Get the selected status from the filter
        d.company_id = $('#workCompany').val() || ''; // Get the selected company from the filter
      }
    },
    // assetsPath + 'json/user-list.json', // JSON file to add data
    columns: [
      // columns according to JSON
      { data: '' },
      // { data: 'id' },
      // { data: 'username' },
      { data: 'company' },
      { data: 'address' },
      { data: 'devices' },
      { data: 'qb' },
      { data: 'type' },
      { data: 'wo_date' },
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
        // Plans
        targets: 1,
        responsivePriority: 1,
        render: function (data, type, full, meta) {
          var $company = full['customer'];

          return '<span class="fw-medium">' + ($company ? $company.company : '') + '</span>';
        }
      },
      {
        // Plans
        targets: 2,
        responsivePriority: 2,
        render: function (data, type, full, meta) {
          var $company = full['customer'];

          return '<span class="fw-medium">' + ($company ? $company.address : '') + '</span>';
        }
      },

      //extra mail
      {
        // Plans
        targets: 3,
        render: function (data, type, full, meta) {
          var $devices = full['devices'].length;

          return '<span class="fw-medium text-capitalized" >' + $devices + '</span>';
        }
      },
      {
        // User Status
        targets: 6,
        responsivePriority: 3,
        render: function (data, type, full, meta) {
          var $date = full['wo_date'] || (full['created_at'] ? moment(full['created_at']).format('YYYY-MM-DD') : '');
          return $date;
        }
      },

      {
        // User Status
        targets: 7,
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
          var loc = "'/manage-work-orders/print/" + id + "','Print','width=650,height=650'";
          return (
            '<div class="d-flex align-items-center text-secondary">' +
            '<a href="javascript:;" onclick="window.open(' +
            loc +
            ');" class="text-secondary print-record"><i class="ti ti-printer ti-sm me-2"></i></a>' +
            '<a href="/manage-work-orders/' +
            id +
            '/edit" class="text-primary"><i class="ti ti-edit ti-sm me-2"></i></a>' +
            '<a href="#" class="send-mail me-2" data-type="1"  data-id="' +
            full['id'] +
            '" data-mail="' +
            full['customer'].primary_email +
            '" >' +
            `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="22" zoomAndPan="magnify" viewBox="0 0 384 383.999986" height="22" preserveAspectRatio="xMidYMid meet" version="1.2"><defs/><g id="b185104ea2"><path style=" stroke:none;fill-rule:nonzero;fill:#000000;fill-opacity:1;" d="M 36 48 C 16.125 48 0 64.125 0 84 C 0 95.324219 5.324219 105.976562 14.398438 112.800781 L 177.601562 235.199219 C 186.148438 241.574219 197.851562 241.574219 206.398438 235.199219 L 369.601562 112.800781 C 378.675781 105.976562 384 95.324219 384 84 C 384 64.125 367.875 48 348 48 Z M 0 132 L 0 288 C 0 314.476562 21.523438 336 48 336 L 336 336 C 362.476562 336 384 314.476562 384 288 L 384 132 L 220.800781 254.398438 C 203.699219 267.226562 180.300781 267.226562 163.199219 254.398438 Z M 0 132 "/><g style="fill:#808080;fill-opacity:1;"><g transform="translate(119.161683, 305.055353)"><path style="stroke:none" d="M 0.03125 0 L 0.03125 -47.714844 L 64.972656 -47.714844 L 64.972656 -36.113281 L 53.367188 -36.113281 L 53.367188 -173.609375 L 64.972656 -173.609375 L 62.042969 -162.378906 L 58.566406 -163.285156 L 61.496094 -174.515625 L 66.515625 -164.054688 C 58.382812 -160.148438 50.355469 -157.050781 42.4375 -154.757812 C 34.5625 -152.476562 25.644531 -150.691406 15.6875 -149.40625 L 2.597656 -147.71875 L 2.597656 -207.65625 L 11.820312 -209.59375 C 34.855469 -214.429688 53.3125 -221.425781 67.191406 -230.585938 L 70.097656 -232.503906 L 119.789062 -232.503906 L 119.789062 -36.113281 L 108.183594 -36.113281 L 108.183594 -47.714844 L 167.382812 -47.714844 L 167.382812 11.605469 L 0.03125 11.605469 Z M 23.238281 0 L 11.632812 0 L 11.632812 -11.605469 L 155.777344 -11.605469 L 155.777344 0 L 144.175781 0 L 144.175781 -36.113281 L 155.777344 -36.113281 L 155.777344 -24.507812 L 96.578125 -24.507812 L 96.578125 -220.902344 L 108.183594 -220.902344 L 108.183594 -209.296875 L 73.582031 -209.296875 L 73.582031 -220.902344 L 79.976562 -211.214844 C 63.636719 -200.433594 42.507812 -192.320312 16.585938 -186.878906 L 14.203125 -198.238281 L 25.808594 -198.238281 L 25.808594 -160.917969 L 14.203125 -160.917969 L 12.71875 -172.425781 C 21.496094 -173.558594 29.25 -175.097656 35.980469 -177.046875 C 42.667969 -178.984375 49.5 -181.628906 56.472656 -184.976562 L 60.308594 -186.816406 L 76.574219 -182.574219 L 76.574219 -24.507812 L 11.632812 -24.507812 L 11.632812 -36.113281 L 23.238281 -36.113281 Z M 23.238281 0 "/></g></g><g style="fill:#ffffff;fill-opacity:1;"><g transform="translate(119.161683, 305.055353)"><path style="stroke:none" d="M 11.640625 0 L 11.640625 -36.109375 L 64.96875 -36.109375 L 64.96875 -173.609375 L 61.5 -174.515625 C 53.9375 -170.890625 46.503906 -168.019531 39.203125 -165.90625 C 31.898438 -163.789062 23.566406 -162.125 14.203125 -160.90625 L 14.203125 -198.234375 C 38.679688 -203.367188 58.472656 -210.921875 73.578125 -220.890625 L 108.1875 -220.890625 L 108.1875 -36.109375 L 155.78125 -36.109375 L 155.78125 0 Z M 11.640625 0 "/></g></g></g></svg>` +
            '</a>' +
            '<a href="#" class="send-mail me-2" data-type="2"  data-id="' +
            full['id'] +
            '" data-mail="' +
            full['customer'].secondary_email +
            '">' +
            `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="22" zoomAndPan="magnify" viewBox="0 0 384 383.999986" height="22" preserveAspectRatio="xMidYMid meet" version="1.2"><defs/><g id="e4605ddd1d"><path style=" stroke:none;fill-rule:nonzero;fill:#000000;fill-opacity:1;" d="M 36 48 C 16.125 48 0 64.125 0 84 C 0 95.324219 5.324219 105.976562 14.398438 112.800781 L 177.601562 235.199219 C 186.148438 241.574219 197.851562 241.574219 206.398438 235.199219 L 369.601562 112.800781 C 378.675781 105.976562 384 95.324219 384 84 C 384 64.125 367.875 48 348 48 Z M 0 132 L 0 288 C 0 314.476562 21.523438 336 48 336 L 336 336 C 362.476562 336 384 314.476562 384 288 L 384 132 L 220.800781 254.398438 C 203.699219 267.226562 180.300781 267.226562 163.199219 254.398438 Z M 0 132 "/><g style="fill:#808080;fill-opacity:1;"><g transform="translate(112.622511, 285.732348)"><path style="stroke:none" d="M -0.886719 -17.71875 C -0.886719 -31.261719 0.648438 -42.835938 3.714844 -52.449219 C 6.910156 -62.453125 12.097656 -71.554688 19.28125 -79.753906 C 25.984375 -87.40625 35.300781 -95.515625 47.230469 -104.085938 L 76.988281 -125.601562 C 84.742188 -131.125 90.6875 -135.84375 94.828125 -139.75 C 98.324219 -143.050781 100.90625 -146.40625 102.574219 -149.816406 C 104.070312 -152.871094 104.820312 -156.5 104.820312 -160.699219 C 104.820312 -166.273438 103.367188 -170.363281 100.464844 -172.972656 C 97.195312 -175.910156 91.558594 -177.378906 83.558594 -177.378906 C 75.167969 -177.378906 67.785156 -175.429688 61.414062 -171.539062 C 54.765625 -167.472656 49.230469 -161.859375 44.816406 -154.695312 L 42.355469 -150.699219 L 27.960938 -146.910156 L -7.1875 -186.261719 L -2.097656 -193.824219 C 6.882812 -207.183594 18.574219 -217.90625 32.976562 -225.996094 C 47.542969 -234.179688 64.960938 -238.269531 85.238281 -238.269531 C 103.746094 -238.269531 119.441406 -235.121094 132.320312 -228.828125 C 145.734375 -222.273438 155.761719 -213.191406 162.40625 -201.582031 C 168.886719 -190.257812 172.125 -177.390625 172.125 -162.988281 C 172.125 -150.53125 169.492188 -139.09375 164.21875 -128.671875 C 159.175781 -118.699219 152.242188 -109.53125 143.414062 -101.164062 C 135.152344 -93.332031 124.671875 -84.96875 111.976562 -76.082031 L 85.546875 -57.597656 C 78.738281 -52.707031 74.128906 -48.578125 71.726562 -45.203125 C 70.023438 -42.8125 69.167969 -40.066406 69.167969 -36.964844 L 57.4375 -36.964844 L 57.4375 -48.699219 L 169.988281 -48.699219 L 169.988281 11.734375 L -0.886719 11.734375 Z M 22.578125 -17.71875 L 22.578125 0 L 10.84375 0 L 10.84375 -11.734375 L 158.253906 -11.734375 L 158.253906 0 L 146.519531 0 L 146.519531 -36.964844 L 158.253906 -36.964844 L 158.253906 -25.234375 L 45.703125 -25.234375 L 45.703125 -36.964844 C 45.703125 -45.070312 48.007812 -52.355469 52.617188 -58.824219 C 56.527344 -64.308594 63.019531 -70.3125 72.097656 -76.828125 L 98.519531 -95.308594 C 110.265625 -103.53125 119.847656 -111.15625 127.269531 -118.191406 C 134.125 -124.691406 139.460938 -131.714844 143.277344 -139.261719 C 146.867188 -146.355469 148.660156 -154.265625 148.660156 -162.988281 C 148.660156 -173.230469 146.453125 -182.210938 142.035156 -189.929688 C 137.78125 -197.363281 131.109375 -203.300781 122.015625 -207.746094 C 112.390625 -212.449219 100.128906 -214.800781 85.238281 -214.800781 C 69.054688 -214.800781 55.464844 -211.714844 44.46875 -205.535156 C 33.3125 -199.269531 24.28125 -191 17.375 -180.730469 L 7.636719 -187.277344 L 16.386719 -195.09375 L 40.675781 -167.902344 L 31.925781 -160.085938 L 28.941406 -171.433594 L 31.84375 -172.199219 L 34.828125 -160.851562 L 24.839844 -167.007812 C 31.21875 -177.359375 39.332031 -185.542969 49.179688 -191.5625 C 59.304688 -197.75 70.765625 -200.84375 83.558594 -200.84375 C 97.550781 -200.84375 108.414062 -197.375 116.144531 -190.433594 C 120.269531 -186.726562 123.359375 -182.261719 125.410156 -177.035156 C 127.328125 -172.15625 128.285156 -166.710938 128.285156 -160.699219 C 128.285156 -152.878906 126.742188 -145.816406 123.65625 -139.503906 C 120.738281 -133.542969 116.5 -127.9375 110.933594 -122.683594 C 106.011719 -118.035156 99.277344 -112.671875 90.738281 -106.585938 L 60.917969 -85.023438 C 50.445312 -77.503906 42.453125 -70.59375 36.933594 -64.292969 C 31.894531 -58.542969 28.273438 -52.214844 26.070312 -45.3125 C 23.742188 -38.019531 22.578125 -28.824219 22.578125 -17.71875 Z M 22.578125 -17.71875 "/></g></g><g style="fill:#ffffff;fill-opacity:1;"><g transform="translate(112.622511, 285.732348)"><path style="stroke:none" d="M 10.84375 -17.71875 C 10.84375 -30.039062 12.191406 -40.425781 14.890625 -48.875 C 17.585938 -57.332031 21.988281 -65.046875 28.09375 -72.015625 C 34.207031 -78.992188 42.867188 -86.507812 54.078125 -94.5625 L 83.859375 -116.09375 C 92.003906 -121.894531 98.34375 -126.9375 102.875 -131.21875 C 107.40625 -135.5 110.816406 -139.976562 113.109375 -144.65625 C 115.398438 -149.34375 116.546875 -154.691406 116.546875 -160.703125 C 116.546875 -169.765625 113.796875 -176.765625 108.296875 -181.703125 C 102.804688 -186.640625 94.5625 -189.109375 83.5625 -189.109375 C 72.96875 -189.109375 63.546875 -186.585938 55.296875 -181.546875 C 47.046875 -176.503906 40.222656 -169.609375 34.828125 -160.859375 L 31.921875 -160.09375 L 7.640625 -187.28125 C 15.578125 -199.09375 25.9375 -208.585938 38.71875 -215.765625 C 51.5 -222.941406 67.003906 -226.53125 85.234375 -226.53125 C 101.941406 -226.53125 115.921875 -223.78125 127.171875 -218.28125 C 138.421875 -212.789062 146.769531 -205.28125 152.21875 -195.75 C 157.664062 -186.226562 160.390625 -175.304688 160.390625 -162.984375 C 160.390625 -152.398438 158.175781 -142.726562 153.75 -133.96875 C 149.320312 -125.207031 143.1875 -117.109375 135.34375 -109.671875 C 127.5 -102.242188 117.46875 -94.253906 105.25 -85.703125 L 78.828125 -67.21875 C 70.878906 -61.507812 65.328125 -56.441406 62.171875 -52.015625 C 59.015625 -47.585938 57.4375 -42.570312 57.4375 -36.96875 L 158.25 -36.96875 L 158.25 0 L 10.84375 0 Z M 10.84375 -17.71875 "/></g></g></g></svg>` +
            '</a>' +
            '<a href="manage-work-orders/' +
            id +
            '" class="text-body open-record"><i class="ti ti-list ti-sm mx-2"></i></a>' +
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
    order: [[6, 'desc']],
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
              columns: [1, 2, 3, 4, 5, 6, 7],
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
              columns: [1, 2, 3, 4, 5, 6, 7],
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
              columns: [1, 2, 3, 4, 5, 6, 7],
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
      }
    ],
    // For responsive popup
    responsive: {
      details: {
        display: $.fn.dataTable.Responsive.display.modal({
          header: function (row) {
            var data = row.data();

            return 'Details of ' + data['customer']['company'];
          }
        }),
        type: 'column',
        renderer: function (api, rowIdx, columns) {
          var dataall = api.data();
          var data = $.map(columns, function (col, i) {
            var result = '';
            //console.log(columns.length + ' : ' + col.columnIndex);

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
          var select = $('#workCompany').on('change', function () {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            column.search(val ? '^' + val + '$' : '', true, false).draw();
          });
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
              '<option value="All">All</option>' +
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
          $('#workCompany').val('').trigger('change');

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

  // send mail
  $(document).on('click', '.send-mail', function () {
    var id = $(this).data('id');
    var mail = $(this).data('mail');
    var type = $(this).data('type');

    if (mail) {
      //first confirm
      Swal.fire({
        title: 'Are you sure?',
        text: 'You want to send mail to ' + mail,
        icon: 'warning',
        showCancelButton: false,
        confirmButtonText: 'Yes, send it!',
        cancelButtonText: 'No, cancel!'
      }).then(function (result) {
        if (result.isConfirmed) {
          $.ajax({
            url: '/manage-work-orders/send-mail/' + id + '/' + type,
            type: 'get',
            success: function (response) {
              if (response.status) {
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
            }
          });
        }
      });
    } else {
      toastr.error('No email found', 'Error', {
        closeButton: true,
        progressBar: true,
        timeOut: 3000,
        positionClass: 'toast-top-right'
      });
    }
  });
  $('.update-company, .update-status , .status-filter').on('change', function () {
    dt_user.ajax.reload();
  });

  // Delete Record
  $(document).on('click', '.delete-record', function () {
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
          url: '/manage-work-orders/delete/' + id,
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
              $('.btn-close').click();
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
  $(document).on('change', '.update-status', function () {
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

  function changeStatus(id, status) {
    $.ajax({
      url: '/manage-work-orders/update-status/' + id,
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
          dt_user.ajax.reload();
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
