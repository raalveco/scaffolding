var App = function(){};

App.prototype.orderedTableInit = function(id_table, position, order){
    var table = $(id_table);

    table.dataTable({
        "pageLength": 10,
        "bStateSave": true,
        "lengthMenu": [
            [10, 20, 30, -1],
            [10, 20, 30, "All"]
        ],
        "pagingType": "bootstrap_full_number",
        "language": {
            "paginate": {
                "previous":"Anterior",
                "next": "Siguiente",
                "last": "Último",
                "first": "Primero"
            },
            "aria": {
                "sortAscending": ": Orden Ascendente",
                "sortDescending": ": Orden Descendente"
            },
            "emptyTable": "No se encontraron registros en esta tabla",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "No se encontraron registros",
            "infoFiltered": "(filtrados de _MAX_ registros)",
            "lengthMenu": "  _MENU_ registros",
            "search": "Buscar:",
            "zeroRecords": "No existen registros que cumplan con el filtro seleccionado"
        },
        "columnDefs": [{
            "searchable": false,
            "targets": [0]
        }],
        "order": [
            [position, order]
        ]
    });

    $(".dataTables_filter").css("float", "right");
    $(".dataTables_paginate").css("float", "right");
    $("label").css("font-size", "12px");
    $('.dataTables_length select').css("font-size", "10px");

    var tableWrapper = jQuery('#sample_1_wrapper');
    tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
};

App.prototype.tableInit = function(id_table, language){
    var table = $(id_table);

    if(language == "en"){
        table.dataTable({
            "pageLength": 10,
            "bStateSave": true,
            "lengthMenu": [
                [10, 20, 30, -1],
                [10, 20, 30, "All"]
            ],
            "pagingType": "bootstrap_full_number",
            "language": {
                "paginate": {
                    "previous":"Prev",
                    "next": "Next",
                    "last": "Last",
                    "first": "First"
                },
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "  _MENU_ records",
                "search": "Search:",
                "zeroRecords": "No matching records found"
            },
            "columnDefs": [{
                'orderable': false,
                'targets': [0]
            }, {
                "searchable": false,
                "targets": [0]
            }],
            "order": [
                [1, "asc"]
            ]
        });
    }

    if(language == "es"){
        table.dataTable({
            "pageLength": 10,
            "bStateSave": true,
            "lengthMenu": [
                [10, 20, 30, -1],
                [10, 20, 30, "All"]
            ],
            "pagingType": "bootstrap_full_number",
            "language": {
                "paginate": {
                    "previous":"Anterior",
                    "next": "Siguiente",
                    "last": "Último",
                    "first": "Primero"
                },
                "aria": {
                    "sortAscending": ": Orden Ascendente",
                    "sortDescending": ": Orden Descendente"
                },
                "emptyTable": "No se encontraron registros en esta tabla",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "No se encontraron registros",
                "infoFiltered": "(filtrados de _MAX_ registros)",
                "lengthMenu": "  _MENU_ registros",
                "search": "Buscar:",
                "zeroRecords": "No existen registros que cumplan con el filtro seleccionado"
            },
            "columnDefs": [{
                'orderable': false,
                'targets': [0]
            }, {
                "searchable": false,
                "targets": [0]
            }],
            "order": [
                [1, "asc"]
            ]
        });
    }

	$(".dataTables_filter").css("float", "right");
    $(".dataTables_paginate").css("float", "right");
    $("label").css("font-size", "12px");
    $('.dataTables_length select').css("font-size", "10px");

	var tableWrapper = jQuery('#sample_1_wrapper');
	tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
}

App.prototype.formValidate = function(id_form, rules, messages){
	// for more info visit the official plugin documentation:
	// http://docs.jquery.com/Plugins/Validation

    $('.form').on('keydown', '.digits', function(e){-1!==$.inArray(e.keyCode,[46,8,27,13])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
    $('.form').on('keydown', '.numeric', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
    $('.upper').keyup(function(e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) ||
                // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }

        this.value = this.value.toUpperCase();
    });

    $('.lower').keyup(function(e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) ||
                // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }

        this.value = this.value.toLowerCase();
    });

    jQuery.validator.addMethod("rfc", function(value, element)
    {
        if (value.length == 12){
            var valid = '^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
        }else{
            var valid = '^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
        }

        var validator=new RegExp(valid);
        var matchArray=value.match(validator);

        var match = false;

        if (matchArray==null) {
            match = false;
        }
        else {
            match = true;
        }

        return this.optional(element) || match;
    }, "El RFC no es válido");

    jQuery.validator.addMethod("letters", function(value, element)
    {
        return this.optional(element) || /^[a-z," "]+$/i.test(value);
    }, "Ingresa solo letras y espacios en blanco");

    $.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-z0-9\s," "]+$/i.test(value);
    }, "Ingresa solo letras, numeros y espacios en blanco");

		var handleWysihtml5 = function() {
		    if (!jQuery().wysihtml5) { return; }
		    if ($('.wysihtml5').size() > 0) {
		        $('.wysihtml5').wysihtml5({
		            "stylesheets": ["../../assets/global/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]
		        });
		    }
		}

		handleWysihtml5();

	    var form = $(id_form);
	    var errors = $('.alert-danger', form);
	    var success = $('.alert-success', form);

	    //IMPORTANT: update CKEDITOR textarea with actual content before submit
	    form.on('submit', function() {
	        for(var instanceName in CKEDITOR.instances) {
	            CKEDITOR.instances[instanceName].updateElement();
	        }
	    })

	    form.validate({
	        errorElement: 'span', //default input error message container
	        errorClass: 'help-block help-block-error', // default input error message class
	        focusInvalid: false, // do not focus the last invalid input
	        ignore: "", // validate all fields including form hidden input
	        rules: rules,
	        messages: messages,
	        errorPlacement: function (error, element) { // render error placement for each input type
	            if (element.parent(".input-group").size() > 0) {
	                error.insertAfter(element.parent(".input-group"));
	            } else if (element.attr("data-error-container")) {
	                error.appendTo(element.attr("data-error-container"));
	            } else if (element.parents('.radio-list').size() > 0) {
	                error.appendTo(element.parents('.radio-list').attr("data-error-container"));
	            } else if (element.parents('.radio-inline').size() > 0) {
	                error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
	            } else if (element.parents('.checkbox-list').size() > 0) {
	                error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
	            } else if (element.parents('.checkbox-inline').size() > 0) {
	                error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
	            } else {
	                error.insertAfter(element); // for other inputs, just perform default behavior
	            }
	        },

	        invalidHandler: function (event, validator) { //display error alert on form submit
	            success.hide();
	            errors.show();
	            Metronic.scrollTo(errors, -200);
	        },

	        highlight: function (element) { // hightlight error inputs
	           $(element)
	                .closest('.form-group').addClass('has-error'); // set error class to the control group
	        },

	        unhighlight: function (element) { // revert the change done by hightlight
	            $(element)
	                .closest('.form-group').removeClass('has-error'); // set error class to the control group
	        },

	        success: function (label) {
	            label
	                .closest('.form-group').removeClass('has-error'); // set success class to the control group
	        },

	        submitHandler: function (form) {
	            success.show();
	            errors.hide();
	            form[0].submit(); // submit the form
	        }

	    });

	     //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
	    $('.select2me', form).change(function () {
	        form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
	    });

	    // initialize select2 tags
	    $("#select2_tags").change(function() {
	        form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
	    }).select2({
	        tags: ["red", "green", "blue", "yellow", "pink"]
	    });

	    //initialize datepicker
	    $('.date-picker').datepicker({
	        rtl: Metronic.isRTL(),
	        autoclose: true
	    });

	    $('.date-picker .form-control').change(function() {
	        form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
	    })
}

App.prototype.notificationsInit = function(direction){
    toastr.options = {
        positionClass: 'toast-'+direction,
        onclick: null,
        closeButton : true,
        showEasing : 'swing',
        hideEasing : 'linear',
        showMethod : 'fadeIn', 
        hideMethod : 'fadeOut', 
        showDuration : 1000,
        hideDuration : 1000,
        timeOut : 5000,
        extendedTimeOut : 1000
    };
}

App.prototype.throwConfirmationModal = function(title,message,action, id){
	var modal = $('#modal-delete');
	var elem_title = modal.find('.modal-title');
	var elem_message = modal.find('.modal-body');
	var elem_action = modal.find('.deleteForm');
    var elem_id = modal.find('#modal-delete-id');
	elem_title.html(title);
	elem_message.html(message);
	elem_action.attr('action',action);
    elem_id.val(id);
	modal.modal('show');
}

App.prototype.throwModal = function(title,message){
	var modal = $('#modal-general');
	var elem_title = modal.find('.modal-title');
	var elem_message = modal.find('.modal-body');
	elem_title.html(title);
	elem_message.html(message);
	modal.modal('show');
}