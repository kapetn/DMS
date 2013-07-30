$(document).ready(function(){ 	

 //Lightbox
 $(function() {
	// Use this example, or...
	//$('a[@rel*=lightbox]').lightBox(); // Select all links that contains lightbox in the attribute rel
	// This, or...
	//$('#gallery a').lightBox(); // Select all links in object with gallery ID
	// This, or...
	$('a.lightbox').lightBox(); // Select all links with lightbox class
	//$('img.lightbox').lightBox(); // Select all links with lightbox class
	// This, or...
	//$('a').lightBox(); // Select all links in the page
	// ... The possibility are many. Use your creative or choose one in the examples above
});

 $("#register").click(function(){
	 $( "#dialog-form" ).dialog( "open" );
  });
  
  $("#buyit").click(function(){
	 $( "#buyit-form" ).dialog( "open" );
  });
  
  $( "#buyit-form" ).dialog({
		autoOpen: false,
		show: {
			effect: "blind",
			duration: 1000
		},
		height: 300,
		width: 450,
		modal: true,
		close: function() {
			//allFields.val( "" ).removeClass( "ui-state-error" );
		},open: function(){
			var pmodule;
			var psessionid;
			var totalprice;
			var productid;
			var htmlresult;
			var hiddenfeatures=" ";	
			var squareprice;
			
			totalprice = $("#newprice").html();
			productid = $("#productid").html();
			//hiddenfeatures = $("#hiddenfeatures").html();
			frmtype = $("#bfrmtype").val();
			pmodule = $("#bpmodule").val();
			psessionid = $("#bpsessionid").val();
			hiddenfeatures = "";
			squareprice = $("#squareprice").html();
			fwidth = $("#fwidth").val();
			fheight = $("#fheight").val();
			
			/*Square Meter price case*/
			if (squareprice>0){
				totalprice = squareprice;
			}
			
			//alert(productid);
			
			$(".pfeat:checked").each(function(){
				//hiddenfeatures  = $(this).val();
				hiddenfeatures  += $(this).attr("title") + "<br>";
			});
			
			//Do the final validation
			if (pmodule!="" && psessionid!=""){
				$.post("http://www.safehouse.com.gr/controller.php", 
				{
				  frmtype : frmtype,
				  pmodule : pmodule,
				  psessionid: psessionid,
				  totalprice: totalprice,
				  productid: productid
				  ,hiddenfeatures: hiddenfeatures
				  ,squareprice: squareprice
				  ,fwidth: fwidth
				  ,fheight: fheight
				  
				},
				function(result){
					if (result == "ok"){
						$("#frmbuyit").css("display", "none");
						$("#bresult").html("Έγινε η εισαγωγή του προϊόντος στο καλάθι αγορών. Ευχαριστούμε.");
						setInterval(function(){
							$("#buyit-form").dialog( "close" );
							setInterval(function(){
								location.reload();
							}, 1000);
						}, 4000);
						//refresh page
						//location.reload();
					}else{
						//$("#lresult").html("Πληκτρολογήσατε λανθασμένο email ή συνθηματικό. Παρακαλούμε προσπαθήστε πάλι.");
					}
	      			
				});
				//alert("ok");
			}//end if
		
		}//end of function open
		
	});
  
  
  $("#logout").click(function(){
  	$("#logout-form" ).dialog( "open" );
  });

  $( "#logout-form" ).dialog({
		autoOpen: false,
		show: {
			effect: "blind",
			duration: 1000
		},
		height: 300,
		width: 450,
		modal: true,
		close: function() {
			//allFields.val( "" ).removeClass( "ui-state-error" );
		},open: function(){
			var pmodule;
			var psessionid;
			var vrc_username_email;
			var htmlresult;
			
			frmtype = $("#lοfrmtype").val();
			pmodule = $("#lοpmodule").val();
			psessionid = $("#lοpsessionid").val();
			vrc_username_email = encodeURIComponent($("#lοvrc_username_email").val());
			//alert(vrc_username_email);
			
			//Do the final validation
			if (pmodule!="" && psessionid!=""){
				$.post("http://www.safehouse.com.gr/controller.php", 
				{
				  frmtype : frmtype,
				  pmodule : pmodule,
				  psessionid: psessionid,
				  vrc_username_email: vrc_username_email
				},
				function(result){
					if (result == "logout ok"){
						$("#frmlogout").css("display", "none");
						$("#loresult").html("Έχετε αποσυνδεθεί επιτυχώς. Ευχαριστούμε.");
						setInterval(function(){
							$("#logout-form").dialog( "close" );
							$("#loginarea").html("<button id='register' class='greycolor btnclass' title='Εγγραφή' value='Εγγραφή'>Εγγραφή</button><button class='btnclass whitecolor border' id='signin' title='Σύνδεση' value='Σύνδεση'>Σύνδεση</button>");
						}, 5000);
						//refresh page
						location.reload();
					}else{
						//$("#lresult").html("Πληκτρολογήσατε λανθασμένο email ή συνθηματικό. Παρακαλούμε προσπαθήστε πάλι.");
					}
	      			
				});
				//alert("ok");
			}//end if
		
		}//end of function open
		
	});
  
  $("#signin").click(function(){
	 $( "#login-form" ).dialog( "open" );
  });

  $( "#login-form" ).dialog({
		autoOpen: false,
		show: {
			effect: "blind",
			duration: 1000
		},
		height: 300,
		width: 450,
		modal: true,
		/*buttons: {
				Submit:{text:"Αποθήκευση"},			 
				"Ακύρωση": function() {
					$( this ).dialog( "close" );
				 }
		},*/
		close: function() {
			//allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});
  
 $("#lcancelbtn").click(function(){
		$("#login-form").dialog("close");
 });	
  
   //Login form validation
   $("#frmlogin").validate({
    rules: {
      vrc_password: {required:true, minlength:5},// simple rule, converted to {required: true}
      vrc_username_email: {required: true, email: true}
    },
    messages: {
      vrc_password: {required:"<br />Το πεδίο είναι υποχρεωτικό", minlength:"<br />Πληκτρολογήστε τουλάχιστον 5 χαρακτήρες"}
      ,vrc_username_email:{required:"<br />Το πεδίο είναι υποχρεωτικό", email:"<br />Το email δεν είναι έγκυρο" }
    }
  });	
  
  
  
  //Complete Order form validation
   $("#frmcompleteorder").validate({
    rules: {
      cvrc_username_email: {required: true, email: true},
      vrc_first_name: {required: true, minlength:3},
      vrc_last_name: {required: true, minlength:3},
      vrc_address: {required: true, minlength:3},
      vrc_city: {required: true, minlength:3},
      vrc_zipcode: {required: true, minlength:3},
      vrc_phone: {required: true, minlength:4}
    },
    messages: {
      cvrc_username_email:{required:"<br />Το πεδίο είναι υποχρεωτικό", email:"<br />Το email δεν είναι έγκυρο" },
      vrc_first_name:{required:"<br />Το πεδίο είναι υποχρεωτικό", minlength:"<br />Ελάχιστοι χαρακτήρες 3"},
      vrc_last_name:{required:"<br />Το πεδίο είναι υποχρεωτικό", minlength:"<br />Ελάχιστοι χαρακτήρες 3"},
      vrc_address:{required:"<br />Το πεδίο είναι υποχρεωτικό", minlength:"<br />Ελάχιστοι χαρακτήρες 3"},
      vrc_city:{required:"<br />Το πεδίο είναι υποχρεωτικό", minlength:"<br />Ελάχιστοι χαρακτήρες 3"},
      vrc_zipcode:{required:"<br />Το πεδίο είναι υποχρεωτικό", minlength:"<br />Ελάχιστοι χαρακτήρες 3"},
      vrc_phone:{required:"<br />Το πεδίο είναι υποχρεωτικό", minlength:"<br />Ελάχιστοι χαρακτήρες 4"}
    }
  });	
  
  
  //Login submit
	$("#lsubmitbtn").click(function(){
		var pmodule;
		var psessionid;
		var vrc_username_email;
		var vrc_password;
		var htmlresult;
		
		frmtype = $("#lfrmtype").val();
		pmodule = $("#lpmodule").val();
		psessionid = $("#lpsessionid").val();
		vrc_username_email = encodeURIComponent($("#vrc_username_email").val());
		//alert(vrc_username_email);
		vrc_password = encodeURIComponent($("#vrc_password").val());
		//alert(vrc_password);
		
		//Do the final validation
		if (pmodule!="" && psessionid!=""){
			$.post("http://www.safehouse.com.gr/controller.php", 
			{
			  frmtype : frmtype,
			  pmodule : pmodule,
			  psessionid: psessionid,
			  vrc_username_email: vrc_username_email,
			  vrc_password : vrc_password
			},
			function(result){
				var twochars;
				var firstname;
				
				twochars = result.substring(0,2);
				firstname = result.substring(3);			
					
				if (twochars == "ok"){
					$("#frmlogin").css("display", "none");
					$("#lresult").html("Έχετε συνδεθεί επιτυχώς. Ευχαριστούμε.");
					setInterval(function(){
						$("#login-form").dialog( "close" );
						$("#loginarea").html("<button id='logout' style='width:150px;' class='greycolor btnclass' title='Αποσύνδεση' value='Αποσύνδεση'>Αποσύνδεση</button><br /><span class='whitecolor'>Καλώς Ήρθες "+firstname+"</span><br />");
					}, 5000);
					//refresh page
					location.reload();
				}else if (result == "login not ok"){
					$("#lresult").html("Πληκτρολογήσατε λανθασμένο email ή συνθηματικό. Παρακαλούμε προσπαθήστε πάλι.");
				}
      			
			});
			//alert("ok");
		}
		
	});
  
 
 
   //Member registration form validation
   $("#frmmember").validate({
    rules: {
      vrc_password: {required:true, minlength:5},// simple rule, converted to {required: true}
      vrc_username_email: {required: true, email: true},
      vrc_first_name: {required: true, minlength:3},
      vrc_last_name: {required: true, minlength:4},
      vrc_address: {required: true, minlength:3},
      vrc_city: {required: true, minlength:3},
      vrc_zipcode: {required: true, minlength:5},
      vrc_phone: {required: true, minlength:10, number:true}
    },
    messages: {
      vrc_password: {required:"<br />Το πεδίο είναι υποχρεωτικό", minlength:"<br />Πληκτρολογήστε τουλάχιστον 5 χαρακτήρες"}
      ,vrc_username_email:{required:"<br />Το πεδίο είναι υποχρεωτικό", email:"<br />Το email δεν είναι έγκυρο" }
      ,vrc_first_name:{required:"<br />Το πεδίο είναι υποχρεωτικό", minlength:"<br />Πληκτρολογήστε τουλάχιστον 3 χαρακτήρες"}
      ,vrc_last_name: {required:"<br />Το πεδίο είναι υποχρεωτικό", minlength:"<br />Πληκτρολογήστε τουλάχιστον 4 χαρακτήρες"}
      ,vrc_address: {required:"<br />Το πεδίο είναι υποχρεωτικό", minlength:"<br />Πληκτρολογήστε τουλάχιστον 3 χαρακτήρες"}
      ,vrc_city: {required:"<br />Το πεδίο είναι υποχρεωτικό", minlength:"<br />Πληκτρολογήστε τουλάχιστον 3 χαρακτήρες"}
      ,vrc_zipcode: {required:"<br />Το πεδίο είναι υποχρεωτικό", minlength:"<br />Πληκτρολογήστε τουλάχιστον 5 χαρακτήρες"}
      ,vrc_phone: {required:"<br />Το πεδίο είναι υποχρεωτικό", number:"<br />Πληκτρολογήστε μόνο αριθμούς χωρίς κενά", minlength:"<br />Πληκτρολογήστε μέχρι 10 αριθμούς"}
    }
  });
  
    $( "#dialog-form" ).dialog({
		autoOpen: false,
		show: {
			effect: "blind",
			duration: 1000
		},
		height: 500,
		width: 450,
		modal: true,
		/*buttons: {
				Submit:{text:"Αποθήκευση"},			 
				"Ακύρωση": function() {
					$( this ).dialog( "close" );
				 }
		},*/
		close: function() {
			//allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});
  
	//Member Registration submit
	$("#submitbtn").click(function(){
	//$("#submitbtn").submit(function(){
		//alert("submit trial");
		var pmodule;
		var psessionid;
		var vrc_username_email;
		var vrc_password;
		var vrc_first_name;
		var vrc_last_name;
		var vrc_address;
		var vrc_city;
		var int_regions;
		var vrc_zipcode;
		var vrc_phone;
		var htmlresult;
		
		frmtype = $("#frmtype").val();
		pmodule = $("#pmodule").val();
		psessionid = $("#psessionid").val();
		vrc_username_email = encodeURIComponent($("#vvrc_username_email").val());
		//alert(vrc_username_email);
		vrc_password = encodeURIComponent($("#vvrc_password").val());
		//alert(vrc_password);
		vrc_first_name = encodeURIComponent($("#vrc_first_name").val());
		vrc_last_name = encodeURIComponent($("#vrc_last_name").val());
		vrc_address = encodeURIComponent($("#vrc_address").val());
		//vrc_address = $("#vrc_address").val();
		vrc_city = encodeURIComponent($("#vrc_city").val());
		int_regions = $("#int_regions").val();
		//alert(int_regions);
		vrc_zipcode = encodeURIComponent($("#vrc_zipcode").val());
		vrc_phone = encodeURIComponent($("#vrc_phone").val());
		//alert(vrc_phone);
		
		//Do the final validation
		if (pmodule!="" && psessionid!=""){
			$.post("http://www.safehouse.com.gr/controller.php", 
			{
			  frmtype : frmtype,
			  pmodule : pmodule,
			  psessionid: psessionid,
			  vvrc_username_email: vrc_username_email,
			  vrc_password : vrc_password,
			  vrc_first_name : vrc_first_name,
			  vrc_last_name : vrc_last_name,
			  vrc_address : vrc_address,
			  vrc_city : vrc_city,
			  int_regions : int_regions,
			  vrc_zipcode : vrc_zipcode,
			  vrc_phone : vrc_phone
			},
			function(result){
				//htmlresult = $("#result").html(result);
				//Member Registration is successfull
				if (result == "member ok"){
					$("#frmmember").css("display", "none");
					$("#result").html("Η εγγραφή μέλους έχει σχεδόν ολοκληρωθεί. Κάντε κλικ στον υπερσύνδεσμο του μηνύματος που θα έρθει σε λίγο στο email σας, για να ολοκληρωθεί η διαδικασία.");
					//$("#dialog-form").hide(5000);
					setInterval(function(){
						$("#dialog-form").dialog( "close" );
					}, 5000);
				}else if (result == "member not ok"){
					//$("#frmmember").css("display", "none");
					$("#result").html("Έχει ήδη ενεργοποιηθεί μέλος με αυτό το email. Παρακαλούμε δοκιμάστε κάποιο άλλο.");
				}
      			
			});
			//alert("ok");
		}
		
	});
	
	$("#cancelbtn").click(function(){
		$("#dialog-form").dialog("close");
	});
	
	
	//Product Total
	var producttotal = 0.00;
	function incptotal(initprice){
		producttotal = parseFloat(initprice) + 0.00;
		initsqprice = $("#initsqprice").html();
		ptotal = parseFloat($("#ptotal").html());
		squareprice = $("#squareprice").html();
		sqmetnet = parseFloat($("#sqprice").html());
		
		if (initsqprice==undefined){
			initsqprice="";
		}	
		
		//alert("sqmetnet:"+sqmetnet+" ptotal"+ptotal);
		
		$(".pfeat:checked").each(function() {
            producttotal = producttotal + parseFloat($(this).val());
        });
        
        if (initsqprice == "0"){
        	producttotal = producttotal + sqmetnet;
        	$("#squareprice").html(producttotal);
        }else{
        	$("#newprice").html(producttotal);
        }
        
        $("#ptotal").html(producttotal);
		//alert(producttotal);
		//$("#newprice").html(producttotal);
	}
	
	function decptotal(initprice){
		producttotal = parseFloat(initprice) + 0.00;
		squareprice = $("#squareprice").html();
		//alert(squareprice);
		
		$(".pfeat:unchecked").each(function() {
            producttotal = producttotal - parseFloat($(this).val());
        });
        
		//alert(producttotal);
		if (squareprice != ''){
			$("#squareprice").html(producttotal);
		}else{
			$("#newprice").html(producttotal);
		}	
	}
	
	//Calc Product Price / Square Meter
	function calcsqprice(){
		$(".dimclass").change(function(){
		 myval = parseFloat($(this).val());
		 alert(myval);
		});	
	
	}
	
	/*Calculate Square Meter Amount*/
	$(".dimclass").change(function(){
		 producttotal = parseFloat($("#ptotal").html());
		 myval = parseFloat($(this).val());
		 initprice = $("#newprice").html();
		 		 
		 fwidth = parseFloat($('#fwidth').val());
		 fheight = parseFloat($('#fheight').val());
		 sqmet = (((fwidth * fheight)*initprice)/10000 ) + producttotal;
		 sqmetnet = (((fwidth * fheight)*initprice)/10000 );
		 producttotal = producttotal+sqmetnet;
		 
		 //alert("producttotal:"+producttotal+" sqmetnet:"+sqmetnet);
		 $("#squareprice").html(producttotal);
		 $("#sqprice").html(sqmetnet);
		 
		 //alert(producttotal);
		 //alert("SM price:"+sqmet);
		 //alert("width:"+fwidth+" height:"+fheight);
		 //alert(initprice);
		 //alert(myval);
	});
	
	$(".pfeat").click(function(){	
		squareprice = $("#initsqprice").html();
		//producttotal = parseFloat($("#ptotal").html());
		
		if (squareprice == '0'){
			incptotal('0');
		}else{
			incptotal($("#initprice").html());
		}	
		//decptotal($("#initprice").html());
		//alert(producttotal);
	});
	
});  