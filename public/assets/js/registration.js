function togglePopup() { 
    $("#name").val('');
    $("#clientEmail").val('');
    $("#phone").val('');
    $("#company").val('');
    $("#comments").val('');
    $('.otpSection').css({'display':'none'});
    $('#sendOtp').show();
    $(".formErrorMessage").hide();
    const overlay = document.getElementById('popupOverlay'); 
    overlay.classList.toggle('show'); 
}
const inputs = document.querySelectorAll(".otp-field input");
inputs.forEach((input, index) => {
    input.dataset.index = index;
    input.addEventListener("keyup", handleOtp);
    input.addEventListener("paste", handleOnPasteOtp);
});
function handleOtp(e) {
    const input = e.target;
    let value = input.value;
    let isValidInput = value.match(/[0-9a-z]/gi);
    input.value = "";
    input.value = isValidInput ? value[0] : "";
    let fieldIndex = input.dataset.index;
    if (fieldIndex < inputs.length - 1 && isValidInput) {
        input.nextElementSibling.focus();
    }
    if (e.key === "Backspace" && fieldIndex > 0) {
        input.previousElementSibling.focus();
    }
    if (fieldIndex == inputs.length - 1 && isValidInput) {
        submit();
    }
}
function handleOnPasteOtp(e) {
    const data = e.clipboardData.getData("text");
    const value = data.split("");
    if (value.length === inputs.length) {
        inputs.forEach((input, index) => (input.value = value[index]));
        submit();
    }
}
function submit() {

    let  otp = "";
    inputs.forEach((input) => {
        otp += input.value;
        input.disabled = true;
        input.classList.add("disabled");
    });

    var datas = {
        'name': $("#name").val(),
        'clientEmail' : $("#clientEmail").val(),
        'phone' : $("#phone").val(),
        'company' : $("#company").val(),
        'comments' : $("#comments").val()
    }

    $.ajax({
        type: "POST",
        url: BASE_URL+'verify-otp',
        data: {'otp':otp,'data':datas},
        dataType: "json",
        success: function(html){
            if(html.data=='valid'){
                $(".otpSection").hide();
                $(".formErrorMessage").show();
                $("#name").val('');
                $("#clientEmail").val('');
                $("#phone").val('');
                $("#company").val('');
                $("#comments").val('');
                $(".formErrorMessage").html('<p style="color:green">Your company has been registered<br>Our executive will contact you shortly.</p><button type="button" onclick="togglePopup()"><a style="color:white;" href="#caregories-section">GO TO VIRTUAL EXHIBITION</a></button>&nbsp;<button type="button" onclick="togglePopup()">CLOSE</button>');
            }else{
                inputs.forEach((input) => {
                    otp += input.value;
                    input.value = '';
                    input.disabled = false;
                });
                $(".formErrorMessage").show();
                $(".formErrorMessage").html('<p style="color:red">Invalid OTP</p>');
                setTimeout(function () {
                    $(".formErrorMessage").fadeOut();
                }, 2500);
            }
        }
    });
}
(function($) {
    $(document).ready(function () { 

        function validateMobile(){
            var regexMobile = /^[+\d](?:.*\d)?$/;
            if (regexMobile.test($("#phone").val())) { 
                return 1;
            }else{
                return 0;
            }
        }
    
        function showResendotp(){
            $('.resend').html('Resend otp after <span class="countdown"></span>');
            var timer2 = "15:00";
            var interval = setInterval(function() {
                var timer = timer2.split(':');
                //by parsing integer, I avoid all extra string processing
                var minutes = parseInt(timer[0], 10);
                var seconds = parseInt(timer[1], 10);
                --seconds;
                minutes = (seconds < 0) ? --minutes : minutes;
                if (minutes < 0) clearInterval(interval);
                seconds = (seconds < 0) ? 59 : seconds;
                seconds = (seconds < 10) ? '0' + seconds : seconds;
                $('.countdown').html(minutes + ':' + seconds);
                timer2 = minutes + ':' + seconds;
                if(timer2=='0:00'){
                    $('.resend').html('<a>Resend OTP</a>');
                    clearInterval(interval);
                }
            }, 1000);
        }
        // Submit button 
        $("#send_otp").click(function () { 

            var ch = 0;
            if ( !validateEmail()	) { 
                $("#clientEmail").css({'border-color':'red'});
                setTimeout(function () {
                    $("#clientEmail").css({'border-color':''});
                }, 2500);
                ch = 1;
            }
            if($("#name").val() == ''){ 
                $("#name").css({'border-color':'red'});
                setTimeout(function () {
                    $("#name").css({'border-color':''});
                }, 2500);
                ch = 1;
            }
            if($("#company").val() == ''){ 
                $("#company").css({'border-color':'red'});
                setTimeout(function () {
                    $("#company").css({'border-color':''});
                }, 2500);
                ch = 1;
            }
            if(validateMobile() == 0){ 
                $("#phone").css({'border-color':'red'});
                setTimeout(function () {
                    $("#phone").css({'border-color':''});
                }, 2500);
                ch = 1;
            }
            if(ch == 0){

                var datas = {
                    'name': $("#name").val(),
                    'clientEmail' : $("#clientEmail").val(),
                    'phone' : $("#phone").val(),
                    'company' : $("#company").val(),
                    'comments' : $("#comments").val()
                }
                $.ajax({
                    method: "POST",
                    url: BASE_URL+'send-otp',
                    data: datas,
                    dataType: "json",
                    success: function(html){
                        
                    }
                });
                $("#sendOtp").hide();
                $(".otpSection").show();
                showResendotp();
            } else{
                $(".formErrorMessage").show();
                $(".formErrorMessage").html('<p style="color:red">Validate the fields</p>');
                setTimeout(function () {
                    $(".formErrorMessage").fadeOut();
                }, 2500);
            }
        }); 

        function validateEmail(){
            // Validate Email 
            var email = document.getElementById("clientEmail"); 
            let regex =  /^([_\-\.0-9a-zA-Z]+)@([_\-\.0-9a-zA-Z]+)\.([a-zA-Z]){2,7}$/; 
            let s = email.value; 
            if (regex.test(s)) { 
                email.classList.remove("is-invalid"); 
                return true; 
            } else { 
                email.classList.add("is-invalid"); 
                return false; 
            } 
        }
    });

})(jQuery); // End jQuery