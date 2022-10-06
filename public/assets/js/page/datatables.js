"use strict";

$("[data-checkboxes]").each(function () {
  var me = $(this),
    group = me.data('checkboxes'),
    role = me.data('checkbox-role');

  me.change(function () {
    var all = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"])'),
      checked = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"]):checked'),
      dad = $('[data-checkboxes="' + group + '"][data-checkbox-role="dad"]'),
      total = all.length,
      checked_length = checked.length;

    if (role == 'dad') {
      if (me.is(':checked')) {
        all.prop('checked', true);
      } else {
        all.prop('checked', false);
      }
    } else {
      if (checked_length >= total) {
        dad.prop('checked', true);
      } else {
        dad.prop('checked', false);
      }
    }
  });
});


$("#table-user").dataTable({
  "language": {
		"url": "//cdn.datatables.net/plug-ins/1.11.2/i18n/pt_br.json",
	},
  
  "columnDefs": [
    { "sortable": false, "targets": [1,4,5] }
  ],
  "columns": [
    { "type": "num" },
    null,
    null,
    null,
    null,
    null
  ],
  "order": [[ 0, "asc" ]]
});

$("#table-obras").dataTable({
  "language": {
		"url": "//cdn.datatables.net/plug-ins/1.11.2/i18n/pt_br.json",
	},
  
  "columnDefs": [
    { "sortable": false, "targets": [1, 7] }
  ],
  "columns": [
    { "type": "num" },
    null,
    null,
    null,
    null,
    null,
    null
  ],
  "pageLength": 25
});

$(".table-acervo").dataTable({
  "language": {
		"url": "//cdn.datatables.net/plug-ins/1.11.2/i18n/pt_br.json",
	},
  
  "columnDefs": [
    { "sortable": false, "targets": [1, 7] }
  ],

  "pageLength": 25
});
