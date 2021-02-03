$(document).ready(
	function(){
		$('.form__date-input').change(
			function(e){
				setDeliveryDate(e.target.value)
			}
		);
		$('.form__region-select').change(
			function(e){
				let departureDate = $('input[name="departure_date"]').val();
				if(departureDate){	
					setDeliveryDate(departureDate)
				}
			}
		);
		$('#addTrip').submit(
			function(e){
				e.preventDefault();
				// if (request) {
						// request.abort();
				// }
				let $form = $(this);
				let $inputs = $form.find("input, select, button, textarea");
				let  serializedData = $form.serialize();
				let formData = new FormData();
				let id_curiers = +$('select[name="curier_selection"]  option:selected').data('id')
				//formData.set('id_curiers', id_curiers);
				
				let id_regions = + $('.form__region-select option:selected').data('id');
				//formData.set('id_regions', id_regions);
				
				let date = $('input[name="departure_date"]').val();
				console.log(serializedData);
				let request = $.ajax(
					{
						type: "POST",
						url: "/addtrip.php",
						processData: false,
						dataType: 'json',
						data: serializedData,
					});
					request.done(function(msg){
						if(msg.status === 'error'){
							alert(msg.msg);
						}
					});
					request.fail(function(XMLHttpRequest, textStatus, errorThrown) { 
						console.log("Status: " + textStatus); 
						console.log("Error: " + errorThrown); 
					}) 
					
			});
		$('#drop_range').click(
			function(e){
				window.location = '/index.php';
			}
		)
		
	}
);
function setDeliveryDate(departureDate){
	let date = new Date(departureDate);
	let days = + $('.form__region-select option:selected').data('duration');
	let delivery_date = date.setDate(date.getDate() + days);
	let day = date.getDate()<10? '0'+date.getDate():date.getDate();
	let month = +date.getMonth()+1<10? '0'+ (+date.getMonth()+1): +date.getMonth()+1
	$('input[name="delivery_date"]')[0].value = day + '.' + month;
}