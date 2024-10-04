(function($) {
    var xhr;
    var element;
    $(document).ready(function () { 
        $(document).on("click", ".new-appt" , function(e) { 
            element = $(this).attr('data-el');
            e.preventDefault();
            $("#appointmentDate").val($(this).attr('data-date'));
            $("#appointmentTime").val($(this).attr('data-time'));
            $("#appointmentDoctor").val($(this).attr('data-doc'));
        });

        $(document).on("click", ".book_appointment" , function(e) { 
            e.preventDefault();
            var datas = {
                'firstName': $("#firstName").val(),
                'lastName': $("#lastName").val(),
                'emailAddress': $("#emailAddress").val(),
                'phoneNumber': $("#phoneNumber").val(),
                'dob': $("#dob").val(),
                'gender':$('input[name="gender"]:checked').val(),
                'date' : $("#appointmentDate").val(),
                'time':  $("#appointmentTime").val(),
                'docId' : $("#appointmentDoctor").val(),
            };
            $.ajax({
                url: baseUrl + '/admin/book-appointment',
                type: 'post',
                data: datas,
                dataType: "json",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res) {
                    if(res.status == 200){
                        $("#close-modal-reg").click();
                        $(".col-lg-4").each(function() {
                            if($(this).attr('data-el')==element){
                                $("."+element+"").html('<span style="cursor:pointer;" data-id="'+res.appId+'" class="badge bg-success not-available-slot">Booked</span>');
                            }
                        });
                        // location.reload();
                        // $('.new-appt').filter(`[data-date="' + datas.date + '"][data-doc="'+datas.docId+'"]`).parent().html('<span class="badge bg-success">Booked</span>');

                        // $("."+element+"").html('<span class="badge bg-success">Booked</span>');
                    }else{
                        $("#errors").html('Something went wrong.');
                    }
                }
            });
        });

        $(document).on("click", ".not-available" , function(e) { 
            if(confirm("Do you want to Remove this time-slot?")){
                e.preventDefault();
                var docId = $(this).attr('data-doc');
                var date = $(this).attr('data-date');
                var time = $(this).attr('data-time');
                var element = $(this).attr('data-el');
                $.ajax({
                    url: baseUrl + '/admin/slot-not-available',
                    type: 'post',
                    data: {'docId':docId,'date':date,'time':time},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function( html ) {
                        $("."+element+"").html('<span style="cursor:pointer;" data-id="'+html+'" class="badge bg-secondary not-available-slot">Not Available</span>');
                    }
                });
            }
        });

        $(document).on("click", ".not-available-slot" , function(e) { 
            if(confirm("Do you want to Enable this time-slot?")){
                e.preventDefault();
                var element = $(this);
                var id = element.attr('data-id');
                $.ajax({
                    url: baseUrl + '/admin/enable-slot',
                    type: 'post',
                    data: {'id':id},
                    dataType: "json",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function( html ) {
                        var parentElement = element.parents('.col-lg-4');
                        var elementAttr = parentElement.attr('data-el');
                        var str = '<button class="new-appt button" data-el="'+elementAttr+'" data-date="'+html.book_date+'"  data-time="'+html.book_time+'" data-doc="'+html.doc_id+'" data-bs-toggle="modal" data-bs-target="#registrationModal">'+
                                '<span class="button-text">Book</span>'+
                                '</button> '+
                                '<button class="not-available button" data-el="'+elementAttr+'" data-date="'+html.book_date+'"  data-time="'+html.book_time+'" data-doc="'+html.doc_id+'">'+
                                '<span class="button-text">Not Available</span>'+
                                '</button>';
                                element.parents('.col-lg-4').html(str);
                    }
                });
            }
        });

        $(document).on("click", ".booked-slot" , function(e) { 
            if(confirm("Do you want to cancel this appointment?")){
                e.preventDefault();
                var element = $(this);
                var id = element.attr('data-id');
                $.ajax({
                    url: baseUrl + '/admin/cancel-appointment',
                    type: 'post',
                    data: {'id':id},
                    dataType: "json",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function( html ) {
                        var parentElement = element.parents('.col-lg-4');
                        var elementAttr = parentElement.attr('data-el');
                        var str = '<button class="new-appt button" data-el="'+elementAttr+'" data-date="'+html.book_date+'"  data-time="'+html.book_time+'" data-doc="'+html.doc_id+'" data-bs-toggle="modal" data-bs-target="#registrationModal">'+
                                '<span class="button-text">Book</span>'+
                                '</button> '+
                                '<button class="not-available button" data-el="'+elementAttr+'" data-date="'+html.book_date+'"  data-time="'+html.book_time+'" data-doc="'+html.doc_id+'">'+
                                '<span class="button-text">Not Available</span>'+
                                '</button>';
                                element.parents('.col-lg-4').html(str);
                    }
                });
            }
        });
    });

    $(document).on("change keyup", "#emailAddress" , function(e) { 
        var emailAddress = $(this).val();
        if (xhr != null){ 
            xhr.abort();
        }
        xhr = $.ajax({
            url: baseUrl + '/admin/get-user-data',
            type: 'post',
            data: {'emailAddress':emailAddress},
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function( html ) {
                if(html){
                    var data = html[0];
                    $("#firstName").val(data.first_name);
                    $("#lastName").val(data.last_name);
                    $("#phoneNumber").val(data.mobile);
                    $("#dob").val(data.dob);
                    $('input[name=gender]').each(function(){
                        if($(this).val() == data.gender){
                            $(this).prop('checked',true);
                            return false;
                        }
                    });
                }
            }
        });
    });
    $(document).on("change keyup", "#phoneNumber" , function(e) { 
        var mobile = $(this).val();
        if (xhr != null){ 
            xhr.abort();
        }
        xhr = $.ajax({
            url: baseUrl + '/admin/get-user-data',
            type: 'post',
            data: {'mobile':mobile},
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function( html ) {
                if(html){
                    var data = html[0];
                    $("#firstName").val(data.first_name);
                    $("#lastName").val(data.last_name);
                    $("#emailAddress").val(data.email);
                    $("#dob").val(data.dob);
                    $('input[name=gender]').each(function(){
                        if($(this).val() == data.gender){
                            $(this).prop('checked',true);
                            return false;
                        }
                    });
                }
            }
        });
    });
    $('.booked-calendar-wrap').on('click', '.page-right, .page-left, .monthName a', function(e) {
        e.preventDefault();
        var jQuerybutton 		= $(this),
            gotoMonth			= jQuerybutton.attr('data-goto'),
            thisCalendarWrap 	= jQuerybutton.parents('.booked-calendar-wrap'),
            thisCalendarDefault = thisCalendarWrap.attr('data-default'),
            calendar_id			= jQuerybutton.parents('table.booked-calendar').attr('data-calendar-id');

        if (typeof thisCalendarDefault == 'undefined'){ thisCalendarDefault = false; }
        var args = {
            'gotoMonth'		: gotoMonth
        };

        savingState(true,thisCalendarWrap);

        $.ajax({
            url: baseUrl + '/get-calendar',
            type: 'post',
            data: args,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function( html ) {
                thisCalendarWrap.html( html );
                adjust_calendar_boxes();
                bookedRemoveEmptyTRs();
                // init_tooltips(thisCalendarWrap);
                $(window).trigger('booked-load-calendar', args, jQuerybutton );
            }
        });
    });
    
    // Saving state updater
    function savingState(show,limit_to){

        show = typeof show !== 'undefined' ? show : true;
        limit_to = typeof limit_to !== 'undefined' ? limit_to : false;
        if (limit_to){
            var jQuerysavingStateDIV = limit_to.find('li.active .savingState, .topSavingState.savingState, .calendarSavingState');
            var jQuerystuffToHide = limit_to.find('.monthName');
            var jQuerystuffToTransparent = limit_to.find('table.booked-calendar tbody');
        } else {
            var jQuerysavingStateDIV = $('li.active .savingState, .topSavingState.savingState, .calendarSavingState');
            var jQuerystuffToHide = $('.monthName');
            var jQuerystuffToTransparent = $('table.booked-calendar tbody');
        }
        if (show){
            jQuerysavingStateDIV.fadeIn(200);
            jQuerystuffToHide.hide();
            jQuerystuffToTransparent.animate({'opacity':0.2},100);
        } else {
            jQuerysavingStateDIV.hide();
            jQuerystuffToHide.show();
            jQuerystuffToTransparent.animate({'opacity':1},0);
        }

    }
    function bookedRemoveEmptyTRs(){
        $('table.booked-calendar').find('tr.week').each(function(){
            if ($(this).children().length == 0){
                $(this).remove();
            }
        });
    }

    function adjust_calendar_boxes(){
        $('.booked-calendar').each(function(){
            var windowWidth = $(window).width();
            var smallCalendar = $(this).parents('.booked-calendar-wrap').hasClass('small');
            var boxesWidth = $(this).find('tbody tr.week td').width();
            var calendarHeight = $(this).height();
            boxesHeight = boxesWidth * 1;
            $(this).find('tbody tr.week td').height(boxesHeight);
            $(this).find('tbody tr.week td .date').css('line-height',boxesHeight+'px');
            $(this).find('tbody tr.week td .date .number').css('line-height',boxesHeight+'px');
            if (smallCalendar || windowWidth < 720){
                $(this).find('tbody tr.week td .date .number').css('line-height',boxesHeight+'px');
            } else {
                $(this).find('tbody tr.week td .date .number').css('line-height','');
            }

            var calendarHeight = $(this).height();
            $(this).parent().height(calendarHeight);

        });
    }
    // Calendar Date Click
    $('.booked-calendar-wrap').on('click', 'tr.week td', function(e) {
			
        e.preventDefault();

        var jQuerythisDate 				= $(this),
            booked_calendar_table 	= jQuerythisDate.parents('table.booked-calendar'),
            jQuerythisRow				= jQuerythisDate.parent(),
            date					= jQuerythisDate.attr('data-date'),
            calendar_id				= booked_calendar_table.attr('data-calendar-id'),
            colspanSetting			= jQuerythisRow.find('td').length;
            docId                   = $('#docId').val();
            specId                   = $('#specId').val();

        if (!calendar_id){ calendar_id = 0; }

        if (jQuerythisDate.hasClass('blur') || jQuerythisDate.hasClass('booked') && !booked_js_vars.publicAppointments || jQuerythisDate.hasClass('prev-date')){

            // Do nothing.

        } else if (jQuerythisDate.hasClass('active')){

            jQuerythisDate.removeClass('active');
            $('tr.entryBlock').remove();

            var calendarHeight = booked_calendar_table.height();
            booked_calendar_table.parent().height(calendarHeight);

        } else {
            $('tr.week td').removeClass('active');
            jQuerythisDate.addClass('active');

            $('tr.entryBlock').remove();
            jQuerythisRow.after('<tr class="entryBlock booked-loading"><td colspan="'+colspanSetting+'"></td></tr>');
            // $('tr.entryBlock').find('td').spin('booked');

            booked_load_calendar_date_booking_options = {'date':date,'calendar_id':calendar_id,'docId':docId,'specId':specId};
            $(document).trigger("booked-before-loading-calendar-booking-options");

            var calendarHeight = booked_calendar_table.height();
            booked_calendar_table.parent().height(calendarHeight);

            $.ajax({
                url: baseUrl + '/admin/get-doctor-apointment',
                type: 'post',
                data: booked_load_calendar_date_booking_options,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( html ) {

                    $('tr.entryBlock').find('td').html( html );

                    $('tr.entryBlock').removeClass('booked-loading');
                    $('tr.entryBlock').find('.booked-appt-list').fadeIn(300);
                    $('tr.entryBlock').find('.booked-appt-list').addClass('shown');
                    adjust_calendar_boxes();

                }
            });
        }
	});
})(jQuery); // End jQuery