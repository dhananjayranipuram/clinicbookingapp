(function($) {
    $(document).ready(function () { 
        $(document).on("click", ".new-appt" , function(e) { 
            e.preventDefault();
            var docId = $(this).attr('data-doc');
            $("#appointmentDate").val($(this).attr('data-date'));
            $("#appointmentTime").val($("#timeslot_"+docId).val());
            $("#appointmentDoctor").val(docId);
            // alert($(this).attr('data-timeslot'))
            $.ajax({
                url: baseUrl + '/check-user',
                type: 'post',
                data: [],
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( html ) {
                    if(html=='true'){
                        $("#appointmentForm").submit();
                    }else{
                        toggleRegistration();
                    }
                }
            });
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
            docId                   = $('#appointmentDoctor').val();

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

            booked_load_calendar_date_booking_options = {'action':'booked_calendar_date','date':date,'calendar_id':calendar_id};
            $(document).trigger("booked-before-loading-calendar-booking-options");

            var calendarHeight = booked_calendar_table.height();
            booked_calendar_table.parent().height(calendarHeight);

            $.ajax({
                url: baseUrl + '/get-doctor-apointment',
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