$(function() {
	$("#btn-get-cae").click(function() {
		var id_presupuesto = $("#btn-get-cae").val();
		getCae(id_presupuesto);
	});

	function getCae(id_presupuesto){
		$.ajax({
				type: "POST",
				url: 'http://localhost/sarmiento-nuevo/index.php/afipFactuaElectronica/getCAE/'+id_presupuesto,
				// data : { presupuesto : presupuesto },
				complete: function(response)
				{
					 console.log(response);
					 location.reload();
				}
		});
	}
});
