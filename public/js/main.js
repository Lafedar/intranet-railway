document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
      locale: 'es',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },

    displayEventTime: false,
    navLinks: true, 
    selectMirror: true,
    editable: true,
    dayMaxEvents: true,

        
    dateClick:function(info){
         
        limpiarFormulario(info)
        $('#txtFecha').val(info.dateStr);
        $('#btnAgregar').prop("disabled", false);
        $('#btnModificar').prop("disabled", true);
        $('#btnBorrar').prop("disabled", true);
        $('#exampleModal').modal();
    },

    eventClick: function(info){

        $("#btnAgregar").prop("disabled",true);
        $("#btnModificar").prop("disabled",false);
        $("#btnBorrar").prop("disabled",false);
        $('#txtID').val(info.event.id);
        $('#txtSala').val(info.event.extendedProps.sala);
        $('#txtPedido_por').val(info.event.extendedProps.pedido_por);
        $('#txtTitulo').val(info.event.extendedProps.titulo);      
      
      mes = (info.event.start.getMonth()+1);
      dia =(info.event.start.getDate());
      anio = (info.event.start.getFullYear());
      mes=(mes<10)?"0"+mes:mes;
      dia=(dia<10)?"0"+dia:dia;

      minutos=info.event.start.getMinutes();
      hora=info.event.start.getHours();
      minutos=(minutos >= 0 && minutos<10)?"0"+minutos:minutos;
      hora=(hora<10)?"0"+hora:hora;
      horario=(hora+":"+minutos+":00");
      

      minutose=info.event.end.getMinutes();
      horae=info.event.end.getHours();
      minutose=(minutose >= 0 && minutose<10)?"0"+minutose:minutose;
      horae=(horae<10)?"0"+horae:horae;
      horarioe=(horae+":"+minutose);
              
      $('#txtHoras').val(horario);
      $('#txtFecha').val(anio+"-"+mes+"-"+dia);
      $('#txtHoraf').val(horarioe);
      $('#txtColor').val(info.event.backgroundColor);
      $('#txtDescripcion').val(info.event.extendedProps.descripcion);
      $('#exampleModal').modal();


    },
    events:url_show
    

  });
 
  calendar.render();
  $('#btnAgregar').click(function () {
    var ObjEvento = recolectarDatosGUI("POST");
    if (ObjEvento !== null) {
        EviarInformacion('', ObjEvento);
    }
});

$('#btnBorrar').click(function() {
    var ObjEvento = recolectarDatosGUI("DELETE");
    if (ObjEvento !== null) {
        EviarInformacion('/' + $('#txtID').val(), ObjEvento);
    }
});

$('#btnModificar').click(function() {
    var ObjEvento = recolectarDatosGUI("PATCH");
    if (ObjEvento !== null) {
        EviarInformacion('/' + $('#txtID').val(), ObjEvento);
    }
});


function recolectarDatosGUI(method) {
    if ($('#txtTitulo').val() === " " || $('#txtHoras').val() === "" || $('#txtHoraf').val() === ""|| $('#txtPedido_por').val() === " " || $('#txtID').val() === " "|| $('#txtSala').val() === " ")
    {
        alert("Por favor, complete todos los campos.");
        return null; 
    }


  var horaInicioObj = new Date("1970-01-01T" + $('#txtHoras').val());
  var horaFinObj = new Date("1970-01-01T" + $('#txtHoraf').val());

  if (horaInicioObj >= horaFinObj) {
      alert("La hora de comienzo debe ser menor que la hora de fin.");
      return null;
  }

  var colo = ($('#txtSala').val() == 'Auditorio') ? '#1569C7' : '#FFDFDD';
  colo = ($('#txtSala').val() == 'Sala Vidriada') ? '#48CCCD' : colo;
  colo = ($('#txtSala').val() == 'Sala Protocolar') ? '#98AFC7' : colo;
  colo = ($('#txtSala').val() == 'Sala Vidridada 2') ? '#FFE5B4' : colo;

  var evento = {
    
        sala: $('#txtSala').val(),
      titulo: $('#txtTitulo').val(),
      descripcion: $('#txtDescripcion').val(),
      pedido_por: $('#txtPedido_por').val(),
      color: colo,
      textColor: '#ffffff',
      start: $('#txtFecha').val() + " " + $('#txtHoras').val(),
      end: $('#txtFecha').val() + " " + $('#txtHoraf').val(),
      '_token': $("meta[name='csrf-token']").attr("content"),
      '_method': method
  };
  
  if (method !== "POST") {
      evento.id = $('#txtID').val();
  }

  return evento;
}

function EviarInformacion(accion, objEvento) {
$.ajax({
    type: "POST",
    url: url_ + accion,
    data: objEvento,
    success: function(msg) {
        console.log(msg);
        $('#exampleModal').modal("toggle");
        calendar.refetchEvents();
        alert("Operación realizada con éxito");
    },
    error: function(xhr) {
        alert("Error: " + xhr.responseText);
    }
});


}
    function limpiarFormulario(info){
      $('#txtID').val("");
      $('#txtTitulo').val("");
      $('#txtSala').val("");
      $('#txtDescripcion').val("");
      $('#txtPedido_por').val("");
      $('#txtColor').val("");
      $('#txtHoras').val("");
      $('#txtFecha').val("");
      $('#txtHoraf').val("");
     
      

    }
});

