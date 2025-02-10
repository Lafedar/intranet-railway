@extends('layouts.app')


<link href="{{asset('fullcalendar/lib/main.css')}}" rel='stylesheet' />
<script src="{{asset ('fullcalendar/lib/locales-all.js') }}" type="text/javascript"></script>
<script src="{{asset('fullcalendar/lib/main.js')}}"></script>

<script type="text/javascript" src="{{ URL::asset('/js/modal-jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-popper.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/modal-bootstrap.min.js') }}"></script>
	
<script  type="text/javascript">
  var url_="{{url('/eventos')}}" ;
  var url_show = "{{url('/eventos/show')}}";
</script>

<script src ="{{asset('js/main.js')}}"></script>









@section('content')


<body>
	<div class= "container" id="calendar-container">
  <div class ="row">
	
	<div class = "col"></div>
		<div class = "col-10"><div id = 'calendar' ></div></div>
	<div class = "col"></div>
</div>
</div>
</body>

	@include ('eventos/agregar')
 
  
@endsection