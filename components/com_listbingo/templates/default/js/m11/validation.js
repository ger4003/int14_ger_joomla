window.addEvent('domready',function(){
	$('adSubmitBtn').addEvent('click',function(event){

event.preventDefault();
		
			var lbadform_valid = true;	
			var lbadform = $('adSubmitForm');	

			if(validateImageType)
			{				
				var tags = document.getElementsByTagName("input");
				for(var i = 0; i < tags.length;i++)
				{			
					if(tags[i] && tags[i].type=="file" && tags[i].name=='images[]' && (tags[i].className=='inputtextbox adimg' || tags[i].className=='inputtextbox adimg required invalid') )
					{
						
						var pattern = '^[a-zA-Z0-9 -_\.]+\.('+acceptimagetypes.toLowerCase()+')$';
						if(!(tags[i].value.toLowerCase().match(pattern)) || tags[i].value == '') {							
							tags[i].className = "inputtextbox adimg required invalid";
							
						}
						else
						{							
							tags[i].className = "inputtextbox";
						}
					}						
				}
			}		
							
			$A(lbadform.elements).each(function(els){										
					if(els.hasClass('invalid')){					
						lbadform_valid = false;												
					}
			});

			if(lbadform_valid){
				$('adSubmitForm').submit();
				return false;
			}else{								
				alert(reqFieldMsg);
				return false;
			}	
	});
});
