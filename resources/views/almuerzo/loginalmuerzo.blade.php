@extends('almuerzo.layouts.layout')
@section('seccion')


<head>
	<script type="text/javascript">
  window  .history.forward();
    function sinVueltaAtras(){ window.history.forward(); }
</script>

</head>

<body onload="sinVueltaAtras();" onpageshow="if (event.persisted) sinVueltaAtras();" onunload="">

 <form action= "{{action('AlmuerzoController@elegir')}}" method="POST">
  @csrf
 			 @if(Session::has('error'))						<!-- mensaje error ususario-->
 				 <div class="alert alert-danger">
    			  {!! Session::get('error') !!}
				</div>
			@endif	

			@if(Session::has('message'))					<!-- mensaje menu actualizado-->
				<div class="alert alert-success">
   				{!! Session::get('message') !!}
   			    </div>
			@endif

<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login Almuerzo') }}</div>

				<div class="card-body">
				<div class="col-md-12" align="center">
					
					

					<label><h4>Ingrese si DNI para continuar</h4></label>
					
					<div class="col md-6">
						<input type="text" name="dni" id="dni" required="true" autocomplete="off">
					</div>
					
					<br/>


					<button type="submit" class="btn btn-secondary">entrar</button>

					
									
				</div>
			</div>
		</div>
	</div>
</div>
</div>

</form>
</body>
@endsection


			