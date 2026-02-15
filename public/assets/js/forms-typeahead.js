/**
 * Typeahead (jquery)
 */

'use strict';

$(function () {
  // String Matcher function
  var substringMatcher = function (strs) {
    return function findMatches(q, cb) {
      var matches, substrRegex;
      matches = [];
      substrRegex = new RegExp(q, 'i');
      $.each(strs, function (i, str) {
        if (substrRegex.test(str)) {
          matches.push(str);
        }
      });

      cb(matches);
    };
  };

  $.ajax({
    url: '/manage-checklists/get-types',
    type: 'GET',
    success: function (response) {
      if (response.status) {
        var states = [];
        $.each(response.data, function (index, make) {
          states.push(make);
        });
        $('#device_type').typeahead(
          {
            highlight: true,
            minLength: 0,
            limit: 1000 // Increase the limit to show more items
          },
          {
            name: 'states',
            source: substringMatcher(states),
            limit: 1000 // Increase the limit here as well
          }
        );
      }
    }
  });

  $.ajax({
    url: '/manage-checklists/get-makes',
    type: 'GET',
    success: function (response) {
      if (response.status) {
        var states = [];
        $.each(response.data, function (index, make) {
          states.push(make);
        });
        $('#make').typeahead(
          {
            highlight: true,
            minLength: 0,
            limit: 1000
          },
          {
            name: 'states',
            limit: 1000,
            source: substringMatcher(states)
          }
        );
      }
    }
  });

  // Use typeahead's 'select' event to handle selection from suggestions
  // Destroy previous typeahead instance before initializing a new one to prevent overlap
  $('#make').on('typeahead:select input change', function () {
    var makeId = $(this).val();

    if (makeId) {
      var urlData = makeId;

      $.ajax({
        url: '/manage-checklists/get-models/' + urlData,
        type: 'GET',
        success: function (response) {
          if (response.status) {
            var states = [];
            $.each(response.data, function (index, model) {
              states.push(model);
            });
            // Destroy previous typeahead instance
            $('#model').typeahead('destroy');
            $('#model').typeahead(
              {
                highlight: true,
                minLength: 0,
                limit: 1000
              },
              {
                name: 'states',
                limit: 1000,
                source: substringMatcher(states)
              }
            );
          }
        }
      });
    } else {
      // Destroy previous typeahead instance and clear input
      $('#model').typeahead('destroy');
      $('#model').val('');
    }
  });

  // Basic
  // --------------------------------------------------------------------

  // Custom Template
});
