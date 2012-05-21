Window.onDomReady(function(){		
		
		//var url='index.php?option=com_listbingo&format=raw&task=addons.captcha.front.verifyCaptcha';		

		$('adSubmitBtn').addEvent('click',function(){
			
			var gb_captcha_valid=true;
			var capvalue= document.adSubmitForm.security_number.value;
			var url='index.php?option=com_listbingo&task=addons.captcha.front.verifyCaptcha&cval='+capvalue+'&format=raw&' +new Date();
			
			$('captcha_processing').setText('Validating Captcha...');
			jsonRequest=new Ajax(url,{
				method:'get',
				evalScripts:true,
				onComplete: function( jsonResponse ) {	
								//alert(jsonResponse);
								res = jsonResponse.split(":")
								jsonResponse = res[1];
								if(jsonResponse=='success'){									
									$('security_number').removeClass('invalid');
									$('security_numbermsg').removeClass('invalid');									
									var gb_captcha_form= $('adSubmitForm'); 									
									$A(gb_captcha_form.elements).each(function(els){										
											if(els.hasClass('invalid')){					
												gb_captcha_valid = false;												
											}
									});

									if(gb_captcha_valid){
										$('adSubmitForm').submit();
									}else{								
										return false;
									}									
								}
								else
								{									
									//$('captcha_processing').setText('Invalid Captcha');
									$('security_number').addClass('invalid');
									$('security_numbermsg').addClass('invalid');
									gb_captcha_valid = false;
									return false;
								}
						    }
			
						}).request();	
			

			
		});
		
		document.formvalidator.setHandler('captchaverify', function (value) {	
			var url='index.php?option=com_listbingo&task=addons.captcha.front.verifyCaptcha&cval='+value+'&format=raw';
	
			req=new Ajax(url,{				
				method:'get',
				evalScripts:true,				
				onComplete: function( response ) {		
					
					//alert(response);
					res = response.split(":")
					response = res[1];
					if(response=='success')
					{
						$('security_number').removeClass('invalid');
						$('security_numbermsg').removeClass('invalid');
						
					}
					else
					{
						$('security_number').addClass('invalid');
						$('security_numbermsg').addClass('invalid');
						
					}
			    }

			}).request();
			
			
		});
	});