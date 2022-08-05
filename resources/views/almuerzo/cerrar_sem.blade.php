<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<form action="{{route('cerrarsema')}}" method="POST" autocomplete="off" id="cerrar">
      {{ method_field('PUT')}} {{csrf_field()}}

		@foreach( $almuerzo as $almuerzo)

		<input type="text" name="id_sem"  value="{{$almuerzo->id_sem}}" hidden="true">
		<input type="text" name="activo"  value="0" hidden="true">

		@endforeach

		
	
	</form>
	

</body>
</html>

<script type="text/javascript">
    document.getElementById("cerrar").submit();
  </script>
