// Custom scripts

$(document).ready(function () {



    // MetsiMenu

    $('#side-menu').metisMenu();



    // Collapse ibox function

    $('.collapse-link').click( function() {

        var ibox = $(this).closest('div.ibox');

        var button = $(this).find('i');

        var content = ibox.find('div.ibox-content');

        content.slideToggle(200);

        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');

        ibox.toggleClass('').toggleClass('border-bottom');

        setTimeout(function () {

            ibox.resize();

            ibox.find('[id^=map-]').resize();

        }, 50);

    });



    // Close ibox function

    $('.close-link').click( function() {

        var content = $(this).closest('div.ibox');

        content.remove();

    });



    // Small todo handler

    $('.check-link').click( function(){

        var button = $(this).find('i');

        var label = $(this).next('span');

        button.toggleClass('fa-check-square').toggleClass('fa-square-o');

        label.toggleClass('todo-completed');

        return false;

    });



    // Append config box / Only for demo purpose

    /*$.get("skin-config.html", function (data) {

        $('body').append(data);

    });*/



    // minimalize menu

    $('.navbar-minimalize').click(function () {

        $("body").toggleClass("mini-navbar");

        SmoothlyMenu();

    })



    // tooltips

    $('.tooltip-demo').tooltip({

        selector: "[data-toggle=tooltip]",

        container: "body"

    })



    // Move modal to body

    // Fix Bootstrap backdrop issu with animation.css

    $('.modal').appendTo("body")



    // Full height of sidebar

    function fix_height() {

        var heightWithoutNavbar = $("body > #wrapper").height() - 61;

        $(".sidebard-panel").css("min-height", heightWithoutNavbar + "px");

    }

    fix_height();



    // Fixed Sidebar

    // unComment this only whe you have a fixed-sidebar

            //    $(window).bind("load", function() {

            //        if($("body").hasClass('fixed-sidebar')) {

            //            $('.sidebar-collapse').slimScroll({

            //                height: '100%',

            //                railOpacity: 0.9,

            //            });

            //        }

            //    })



    $(window).bind("load resize click scroll", function() {

        if(!$("body").hasClass('body-small')) {

            fix_height();

        }

    })



    $("[data-toggle=popover]")

        .popover();

});





// For demo purpose - animation css script

function animationHover(element, animation){

    element = $(element);

    element.hover(

        function() {

            element.addClass('animated ' + animation);

        },

        function(){

            //wait for animation to finish before removing classes

            window.setTimeout( function(){

                element.removeClass('animated ' + animation);

            }, 2000);

        });

}



// Minimalize menu when screen is less than 768px

$(function() {

    $(window).bind("load resize", function() {

        if ($(this).width() < 769) {

            $('body').addClass('body-small')

        } else {

            $('body').removeClass('body-small')

        }

    })

})



function SmoothlyMenu() {

    if (!$('body').hasClass('mini-navbar') || $('body').hasClass('body-small')) {

        // Hide menu in order to smoothly turn on when maximize menu

        $('#side-menu').hide();

        // For smoothly turn on menu

        setTimeout(

            function () {

                $('#side-menu').fadeIn(500);

            }, 100);

    } else if ($('body').hasClass('fixed-sidebar')){

        $('#side-menu').hide();

        setTimeout(

            function () {

                $('#side-menu').fadeIn(500);

            }, 300);

    } else {

        // Remove all inline style from jquery fadeIn function to reset menu state

        $('#side-menu').removeAttr('style');

    }

}

function fillLeave(){
	
	$( ".panelGrid" ).each(function( index ) {
		
		$(this).find('.HalfDayAmount').val(0);
		$(this).find('.LeavesAmount').val(0);
		
		var Leaves= $(this).find('.Leaves').val();
		var HalfDay=$(this).find('.HalfDay').val();
		var salary=$(this).find('.BasicSalary').val();
		
		if(Leaves>0){
			$(this).find('.ClAmount').val('0');
        }else if(HalfDay==1){
			$(this).find('.ClAmount').val((salary/60).toFixed(2));	
        }else if(HalfDay>1){
			$(this).find('.ClAmount').val('0');
        }else{
			$(this).find('.ClAmount').val((salary/30).toFixed(2));
        }
		
		
		if($(this).find('.ClAmount').val()=='0'){
			if(Leaves==1){
				$(this).find('.LeavesAmount').val(0);
			}else if(Leaves>1){
				$(this).find('.LeavesAmount').val(((salary/30)*(Leaves-1)).toFixed(2));
				
			}
			
			$(this).find('.HalfDayAmount').val(((salary/60)*(HalfDay)).toFixed(2));
			
			
			if((Leaves==0 || Leaves=='') && (HalfDay==1 || HalfDay==2)){
				$(this).find('.LeavesAmount').val(0);
				$(this).find('.HalfDayAmount').val(0);
				
			}
			
			if((Leaves==0 || Leaves=='') && (HalfDay>2)){
				$(this).find('.LeavesAmount').val(0);
				$(this).find('.HalfDayAmount').val(((salary/60)*(HalfDay-2)).toFixed(2));
				
			}
			
			if(HalfDay=='' || HalfDay==0){
				$(this).find('.HalfDayAmount').val(0);
			}
		}
		
		
     });
	 
	totalsalary(); 
	 
}


function totalsalary(){
	
	 $( ".panelGrid" ).each(function( index ) {
			var LeavesAmount=parseFloat($(this).find('.LeavesAmount').val());
			var HalfDayAmount=parseFloat($(this).find('.HalfDayAmount').val());
			var LoanDeduction=parseFloat($(this).find('.LoanDeduction').val());
			var LateArrival=parseFloat($(this).find('.LateArrival').val());
			var Others=parseFloat($(this).find('.Others').val());
			
			var Leaves=parseFloat($(this).find('.Leaves').val());
			
			if(isNaN(LeavesAmount)!=false && isNaN(HalfDayAmount)!=false){
				var salary=$(this).find('.BasicSalary').val();
				var parday=(salary/30).toFixed(2);
				$(this).find('.ClAmount').val(parday);
			}
			
			if(isNaN(Leaves)==false && Leaves==1 && isNaN(HalfDayAmount)!=false){
				$(this).find('.ClAmount').val(0);
			}
			
			var TotalDeduction=0;
			if(isNaN(LeavesAmount)==false){
				TotalDeduction+=LeavesAmount;
			}
			if(isNaN(HalfDayAmount)==false){
				TotalDeduction+=HalfDayAmount;
			}
			if(isNaN(LoanDeduction)==false){
				TotalDeduction+=LoanDeduction;
			}
			if(isNaN(LateArrival)==false){
				TotalDeduction+=LateArrival;
			}
			if(isNaN(Others)==false){
				TotalDeduction+=Others;
			}
		
			$(this).find('.TotalDeduction').val((TotalDeduction).toFixed(2));
			
		 
			var BasicSalary=parseFloat($(this).find('.BasicSalary').val());
			var Da=parseFloat($(this).find('.Da').val());
			var TA=parseFloat($(this).find('.TA').val());
			var HRA=parseFloat($(this).find('.HRA').val());
			var ClAmount=parseFloat($(this).find('.ClAmount').val());
			var Overtime=parseFloat($(this).find('.Overtime').val());
			var Bonus=parseFloat($(this).find('.Bonus').val());
			
			var total=0;
			if(isNaN(BasicSalary)==false){
				total+=BasicSalary;
			}
			if(isNaN(Da)==false){
				total+=Da;
			}
			if(isNaN(TA)==false){
				total+=TA;
			}
			if(isNaN(HRA)==false){
				total+=HRA;
			}
			if(isNaN(ClAmount)==false){
				total+=ClAmount;
			}
			if(isNaN(Overtime)==false){
				total+=Overtime;
			}
			if(isNaN(Bonus)==false){
				total+=Bonus;
			}
			
			$(this).find('.TotalIncome').val((total).toFixed(2));
			
			if((total-TotalDeduction)>0){
				$(this).find('.NetPay').val(Math.round(total-TotalDeduction));
				$(this).find('.NetPayHtml').val(Math.round(total-TotalDeduction)+'/('+convertNumberToWords(Math.round(total-TotalDeduction))+')' );
				
				
			}else{
				$(this).find('.NetPay').val(0);
				$(this).find('.NetPayHtml').val(0);
			}
			
			
			if($(this).find('.HalfDay').val()=='2'){
				
				$(this).find('.ClAmount').val(0);
			}
			
	
			
	});

	
}

function fillSalaryMonth(){
			
		//Date fillDate

		//2018-04-05	
		
		var months = {};
		months['January']='01';
        months['February']='02';
        months['March']='03';
        months['April']='04';
        months['May']='05';
        months['June']='06';
        months['July']='07';
        months['August']='08';
        months['September']='09';
        months['October']='10';
        months['November']='11';
        months['December']='12';
        
		
		var curnewDate = new Date();
		var curdates=curnewDate.getDate();
		if(curdates<10){
			curdates='0'+curdates;
		}
		
		var dateNew= $('.year').val()+"-"+months[$('.month').val()]+'-'+curdates;
		
		$('.SalaryMonth').val(dateNew);	
	
		checkRecordsAvailable();
}


function togglePanel(obj){
	$(obj).closest('.panelGrid').find('.panel-body').toggle(500);
	
	if($(obj).closest('.panelGrid').find('.fa').hasClass('fa-angle-down')){
		$(obj).closest('.panelGrid').find('.fa').removeClass('fa-angle-down');
		$(obj).closest('.panelGrid').find('.fa').addClass('fa-angle-up');
	}else{
		$(obj).closest('.panelGrid').find('.fa').removeClass('fa-angle-up');
		$(obj).closest('.panelGrid').find('.fa').addClass('fa-angle-down');
	}
}
// Dragable panels
function convertNumberToWords(amount) {
        var words = new Array();
        words[0] = '';
        words[1] = 'One';
        words[2] = 'Two';
        words[3] = 'Three';
        words[4] = 'Four';
        words[5] = 'Five';
        words[6] = 'Six';
        words[7] = 'Seven';
        words[8] = 'Eight';
        words[9] = 'Nine';
        words[10] = 'Ten';
        words[11] = 'Eleven';
        words[12] = 'Twelve';
        words[13] = 'Thirteen';
        words[14] = 'Fourteen';
        words[15] = 'Fifteen';
        words[16] = 'Sixteen';
        words[17] = 'Seventeen';
        words[18] = 'Eighteen';
        words[19] = 'Nineteen';
        words[20] = 'Twenty';
        words[30] = 'Thirty';
        words[40] = 'Forty';
        words[50] = 'Fifty';
        words[60] = 'Sixty';
        words[70] = 'Seventy';
        words[80] = 'Eighty';
        words[90] = 'Ninety';
        amount = amount.toString();
        var atemp = amount.split(".");
        var number = atemp[0].split(",").join("");
        var n_length = number.length;
        var words_string = "";
        if (n_length <= 9) {
            var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
            var received_n_array = new Array();
            for (var i = 0; i < n_length; i++) {
                received_n_array[i] = number.substr(i, 1);
            }
            for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
                n_array[i] = received_n_array[j];
            }
            for (var i = 0, j = 1; i < 9; i++, j++) {
                if (i == 0 || i == 2 || i == 4 || i == 7) {
                    if (n_array[i] == 1) {
                        n_array[j] = 10 + parseInt(n_array[j]);
                        n_array[i] = 0;
                    }
                }
            }
            value = "";
            for (var i = 0; i < 9; i++) {
                if (i == 0 || i == 2 || i == 4 || i == 7) {
                    value = n_array[i] * 10;
                } else {
                    value = n_array[i];
                }
                if (value != 0) {
                    words_string += words[value] + " ";
                }
                if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
                    words_string += "Crores ";
                }
                if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
                    words_string += "Lakhs ";
                }
                if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                    words_string += "Thousand ";
                }
                if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                    words_string += "Hundred and ";
                } else if (i == 6 && value != 0) {
                    words_string += "Hundred ";
                }
            }
            words_string = words_string.split("  ").join(" ");
        }
        return words_string + 'Rupees Only';
    }

function withDecimal(n) {
    var nums = n.toString().split('.')
    var whole = convertNumberToWords(nums[0])
    if (nums.length == 2) {
        var fraction = convertNumberToWords(nums[1])
        return whole + ' and ' + fraction;
    } else {
        return whole;
    }
}
	
function payAllSal (){
	var selids=[];
	$(".selEmp").each(function( index ) {
		if($(this).prop("checked")){
			selids.push($(this).attr('id'));
		}
	});
	if(selids!=undefined && selids.length>0){
		window.location.href='\add_new_staff_salary.php?staff_ids='+selids;
	}else{
		alert("select records");
	}
}	

function checkValidations(){
	var isRecordsAllready=false;
	var isCheque=false;
	var iserrCheck=false;
	var ismonthFur=false;
	var errPanelindex=[];
	
	var index=0;	
	 $( ".panelGrid" ).each(function( index ) {
		 
			var Overtime=parseFloat($(this).find('.Overtime').val());
			var Bonus=parseFloat($(this).find('.Bonus').val());
			
			if(((Overtime!='' && Overtime!=0 && isNaN(Overtime)==false) || (Bonus!='' && Bonus!=0 && isNaN(Bonus)==false)) 
				&& $(this).find('.Description').val().trim()==''){
				iserrCheck=true;
				$(this).find('.Description').css("border","1px solid red");
				errPanelindex.push(index);
				
            }else{
				$(this).find('.Description').css("border","1px solid #e5e6e7");
				
            }
			
			if($(this).find('.Paymentmode').val()=='Cheque' && $(this).find('.cheque').val().trim()=='' ){
				$(this).find('.cheque').css("border","1px solid red");
				isCheque=true;
				errPanelindex.push(index);
			}else{
				$(this).find('.cheque').css("border","1px solid #e5e6e7");
				
			}
			
		index++;	
     });
	
	$(".panelGrid").css("border-color","#1ab394");
			
	for(var i=0;i<errPanelindex.length;i++){
		var panelobj=document.getElementsByClassName("panelGrid")[i];
		$(panelobj).css("border-color","red");
	}


	
	var curDate=new Date();
	$(".SalaryMonth").each(function() {
	   var selDate=new Date($(this).val());	
		if(selDate.getMonth()>curDate.getMonth() && selDate.getFullYear()>=curDate.getFullYear()){
			ismonthFur=true;
		}
	
	});
	
	$(".errmsg").each(function() {
		if($(this).html()!=''){
			isRecordsAllready=true;
		}
	});
		
	if(iserrCheck){
    	alert("Highlight panel description fields fill");
		return false; 
		
	}else if(ismonthFur){
			alert("Date not Selected Future");			
			return false;
	}else if(isRecordsAllready){
		alert("Some employee already paid salary selected month");			
			return false;
	}else if(isCheque){
		alert("Highlight panel Cheque no fill");			
			return false;
	}
	else{		
	
		var staffid='';
		$( ".staffid" ).each(function( index ) {
				staffid+=$(this).val()+',';
		});
		if(staffid!=''){
			staffid=staffid.substr(0,staffid.length-1);
		}
		$(".staffids").val(staffid);

		return true;
    }
	
	
}

function fillDate(){
	
	var currentYear = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
	var preYear = new Date(new Date().getFullYear()- 1, new Date().getMonth(), new Date().getDate());

	$('.year').find('option').remove();
	$('.year').append('<option value="'+preYear.getFullYear()+'">'+preYear.getFullYear()+'</option>');
	$('.year').append('<option value="'+currentYear.getFullYear()+'">'+currentYear.getFullYear()+'</option>');
	
}

function changeMonth(obj){
	if($(obj).val()=='January'){
		var year=parseInt($(".year").val()+'');
		year--;
		$(".year").val(year);
    }
}


function selAllEmps (){
	$(".selEmp").prop("checked",true);
}
function WinMove() {

    var element = "[class*=col]";

    var handle = ".ibox-title";

    var connect = "[class*=col]";

    $(element).sortable(

        {

            handle: handle,

            connectWith: connect,

            tolerance: 'pointer',

            forcePlaceholderSize: true,

            opacity: 0.8,

        })

        .disableSelection();

};





