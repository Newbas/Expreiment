(function(){
	$(document).ready(function(){
		//Ajax submit script for buttons
		$('body').on('click','.ajax-submit',function(e){
			e.preventDefault();
			var form = $(this).parents('form');
			$(form).find('div.errorMessage').text('').hide();
			var data = $(form).serialize();
			//variable to show that it's ajax
			data += '&ajax=1';
			$.ajax({
				'url' : $(form).attr('action'),
				'type': 'POST',
				'data': data,//$(form).serialize(),
				'success' : function(response){			
					response = $.parseJSON(response);
					if(response.ok)
					{
						$(form).find('input[type="password"]').val('');
						$(form).trigger('submitted',[response]);
						if(response.script)
							$('body').after(response.script);
					}
					else
					{
						$.each(response,function(idx,element){
							$('#'+idx).prev('div.errorMessage').show().text(element[0]);
							$('#'+idx).mouseover();
							setTimeout(function(){$('#'+idx).mouseleave();},2000);
						});
					}
				}
			});
			return true;
		});
		
		$('body').on('click','a.ajax',function(e){
			e.preventDefault();
			$.ajax({
				'url' : $(this).attr('href'),
				'type': 'post',
				'success': function(response){
					$('body').append(response);
				}
			});
		});
	  
	});
}());