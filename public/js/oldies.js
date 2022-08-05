$(function(){
	$('#id_f').on('change', SelectComida);

});

function SelectComida(){
	
	var fecha = $(this).val();
	console.log(fecha);

	$.get('/api/comi/'+fecha+'/sel',function(data){
		console.log(data);

		for( var i=0 ; i<data.length ; ++i){

			document.querySelector('#tlun').innerText = data[i].tlun;
			document.querySelector('#tmar').innerText = data[i].tmar;
			document.querySelector('#tmier').innerText = data[i].tmier;
			document.querySelector('#tjue').innerText = data[i].tjue;
			document.querySelector('#tvie').innerText = data[i].tvie;

			document.querySelector('#bclun').innerText = data[i].bclun;
			document.querySelector('#bcmar').innerText = data[i].bcmar;
			document.querySelector('#bcmier').innerText = data[i].bcmier;
			document.querySelector('#bcjue').innerText = data[i].bcjue;
			document.querySelector('#bcvie').innerText = data[i].bcvie;

			document.querySelector('#elun').innerText = data[i].elun;
			document.querySelector('#emar').innerText = data[i].emar;
			document.querySelector('#emier').innerText = data[i].emier;
			document.querySelector('#ejue').innerText = data[i].ejue;
			document.querySelector('#evie').innerText = data[i].evie;
			
			document.querySelector('#mlun').innerText = data[i].mlun;
			document.querySelector('#mmar').innerText = data[i].mmar;
			document.querySelector('#mmier').innerText = data[i].mmier;
			document.querySelector('#mjue').innerText = data[i].mjue;
			document.querySelector('#mvie').innerText = data[i].mvie;

			document.querySelector('#merienda').innerText = data[i].merienda;
			document.querySelector('#colacion').innerText = data[i].colacion;
			//document.querySelector('#y_y_f').innerText = data[i].y_y_f;




			}

	
	
	
	});
}