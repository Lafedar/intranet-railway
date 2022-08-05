
$(function(){
	$('#id_e').on('change', SelectPersona);
	

});


function SelectPersona (){
	var persona_nom = $(this).val();
	console.log(persona_nom);
	
	var personita = persona_nom.replace(/\s+/g, '')
	console.log(personita);
	//AJAX
	$.get('/api/almuerzo/'+persona_nom+'/sel',function(data){
		console.log(data);

		var lunes;
		var martes;
		var miercoles;
		var jueves;
		var viernes;
		var id;
		var id_e;
		var fecha;

		for (var a =0;  a<data.length;++a)
			id=data[a].id;
		$('#id').prop("value",id);
console.log(id);
		
		for (var i =0;  i<data.length;++i)
		dni =data[i].dni;
			$('#dni').prop("value" ,dni);
	console.log(dni);

			
		for (var i =0;  i<data.length;++i)
		lunes =data[i].lunes;
		if(lunes == '1')
			$("#lunes_1").prop("checked",true);
		else if (lunes == '2')
			$("#lunes_2").prop("checked",true);
		else if (lunes == '3')
			$("#lunes_3").prop("checked",true);
		else if (lunes == '4')
			$("#lunes_4").prop("checked",true);
		else if (lunes == '5')
			$("#lunes_5").prop("checked",true);
		else if (lunes == '6')
			$("#lunes_6").prop("checked",true);
		else if (lunes == '7')
			$("#lunes_7").prop("checked",true);
		else{
			$("#lunes_1").prop("checked",false);
			$("#lunes_2").prop("checked",false);
			$("#lunes_3").prop("checked",false);
			$("#lunes_4").prop("checked",false);
			$("#lunes_5").prop("checked",false);
			$("#lunes_6").prop("checked",false);
			$("#lunes_7").prop("checked",false);
			}
		

		
		for (var j =0;  j<data.length;++j)
		martes =data[j].martes;
		if(martes == '1')
			$("#martes_1").prop("checked",true);
		else if (martes == '2')
			$("#martes_2").prop("checked",true);
		else if (martes == '3')
			$("#martes_3").prop("checked",true);
		else if (martes == '4')
			$("#martes_4").prop("checked",true);
		else if (martes == '5')
			$("#martes_5").prop("checked",true);
		else if (martes == '6')
			$("#martes_6").prop("checked",true);
		else if (martes == '7')
			$("#martes_7").prop("checked",true);
		else{
			$("#martes_1").prop("checked",false);
			$("#martes_2").prop("checked",false);
			$("#martes_3").prop("checked",false);
			$("#martes_4").prop("checked",false);
			$("#martes_5").prop("checked",false);
			$("#martes_6").prop("checked",false);
			$("#martes_7").prop("checked",false);
			}

		for (var k =0;  k<data.length;++k)
		miercoles =data[k].miercoles;
		if(miercoles == '1')
			$("#miercoles_1").prop("checked",true);
		else if (miercoles == '2')
			$("#miercoles_2").prop("checked",true);
		else if (miercoles == '3')
			$("#miercoles_3").prop("checked",true);
		else if (miercoles == '4')
			$("#miercoles_4").prop("checked",true);
		else if (miercoles == '5')
			$("#miercoles_5").prop("checked",true);
		else if (miercoles == '6')
			$("#miercoles_6").prop("checked",true);
		else if (miercoles == '7')
			$("#miercoles_7").prop("checked",true);
		else{
			$("#miercoles_1").prop("checked",false);
			$("#miercoles_2").prop("checked",false);
			$("#miercoles_3").prop("checked",false);
			$("#miercoles_4").prop("checked",false);
			$("#miercoles_5").prop("checked",false);
			$("#miercoles_6").prop("checked",false);
			$("#miercoles_7").prop("checked",false);
			}
		
		for (var m =0;  m<data.length;++m)
		jueves =data[m].jueves;
		if(jueves == '1')
			$("#jueves_1").prop("checked",true);
		else if (jueves == '2')
			$("#jueves_2").prop("checked",true);
		else if (jueves == '3')
			$("#jueves_3").prop("checked",true);
		else if (jueves == '4')
			$("#jueves_4").prop("checked",true);
		else if (jueves == '5')
			$("#jueves_5").prop("checked",true);
		else if (jueves == '6')
			$("#jueves_6").prop("checked",true);
		else if (jueves == '7')
			$("#jueves_7").prop("checked",true);
		else{
			$("#jueves_1").prop("checked",false);
			$("#jueves_2").prop("checked",false);
			$("#jueves_3").prop("checked",false);
			$("#jueves_4").prop("checked",false);
			$("#jueves_5").prop("checked",false);
			$("#jueves_6").prop("checked",false);
			$("#jueves_7").prop("checked",false);
			}

		for (var n =0;  n<data.length;++n)
		viernes=data[n].viernes;
				
		if(viernes == '1')
			$("#viernes_1").prop("checked",true);
		else if (viernes == '2')
			$("#viernes_2").prop("checked",true);
		else if (viernes == '3')
			$("#viernes_3").prop("checked",true);
		else if (viernes == '4')
			$("#viernes_4").prop("checked",true);
		else if (viernes == '5')
			$("#viernes_5").prop("checked",true);
		else if (viernes == '6')
			$("#viernes_6").prop("checked",true);
		else if (viernes == '7')
			$("#viernes_7").prop("checked",true);
		else{
			$("#viernes_1").prop("checked",false);
			$("#viernes_2").prop("checked",false);
			$("#viernes_3").prop("checked",false);
			$("#viernes_4").prop("checked",false);
			$("#viernes_5").prop("checked",false);
			$("#viernes_6").prop("checked",false);
			$("#viernes_7").prop("checked",false);
			lunes="undefined";
			martes="undefined";
			miercoles="undefined";
			jueves="undefined";
			viernes="undefined";



			}

			console.log(id);
			console.log(lunes);
			console.log(martes);
			console.log(miercoles);
			console.log(jueves);
			console.log(viernes);


			if(lunes == "undefined" && martes == "undefined" && miercoles == "undefined" && jueves == "undefined" && viernes == "undefined"){
				var guardar = document.getElementById('guardar');
  				var actualizar = document.getElementById('actualizar');
  				actualizar.style.display = 'none';
  				guardar.style.display = 'block';

  			}
  				else{
  					var guardar = document.getElementById('guardar');
  				var actualizar = document.getElementById('actualizar');
  				actualizar.style.display = 'block';
  				guardar.style.display = 'none';
  				}

	  	
	});
	
}  



