<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE></TITLE>
	<META NAME="GENERATOR" CONTENT="LibreOffice 4.1.6.2 (Linux)">
	<META NAME="AUTHOR" CONTENT="C">
	<META NAME="CREATED" CONTENT="20190520;141000000000000">
	<META NAME="CHANGEDBY" CONTENT="C">
	<META NAME="CHANGED" CONTENT="20190520;142100000000000">
	<META NAME="AppVersion" CONTENT="14.0000">
	<META NAME="Company" CONTENT="Microsoft">
	<META NAME="DocSecurity" CONTENT="0">
	<META NAME="HyperlinksChanged" CONTENT="false">
	<META NAME="LinksUpToDate" CONTENT="false">
	<META NAME="ScaleCrop" CONTENT="false">
	<META NAME="ShareDoc" CONTENT="false">
	<STYLE TYPE="text/css">
		<!--
		@page { margin-left: 1.18in; margin-right: 1.18in; margin-top: 0.98in; margin-bottom: 0.98in }
		P { margin-bottom: 0.08in; direction: ltr; widows: 2; orphans: 2 }
		-->
	</STYLE>
</HEAD>
<BODY LANG="es-ES" DIR="LTR">
	<TABLE WIDTH=581 CELLPADDING=7 CELLSPACING=0 STYLE="page-break-before: always">
		<COL WIDTH=565>
		<TR>
			<TD WIDTH=565 VALIGN=BOTTOM STYLE="border: 1.00pt solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
				<P ALIGN=CENTER><FONT FACE="Arial, serif"><B>SOLICITUD DE PERMISO</B></FONT></P>
			</TD>
		</TR>
		<TR>
			<TD WIDTH=565 HEIGHT=77 VALIGN=TOP STYLE="border: 1.00pt solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
				<P STYLE="margin-bottom: 0in"><FONT FACE="Arial, serif"><FONT SIZE=3> <strong>Fecha
				solicitud: </strong>{!! \Carbon\Carbon::parse($permiso->fecha_permiso)->format("d-m-Y") !!}</FONT></FONT></P>
				
				<P STYLE="margin-bottom: 0in"><FONT FACE="Arial, serif"><FONT SIZE=3><strong>Fecha
				desde: </strong>{!! \Carbon\Carbon::parse($permiso->fecha_desde)->format("d-m-Y") !!}</FONT></FONT></P>

				<P STYLE="margin-bottom: 0in"><FONT FACE="Arial, serif"><FONT SIZE=3><strong>Fecha
				hasta: </strong>{!! \Carbon\Carbon::parse($permiso->fecha_hasta)->format("d-m-Y") !!}</FONT></FONT></P>

				<P STYLE="margin-bottom: 0in"><FONT FACE="Arial, serif"><FONT SIZE=3><strong>Horario: De </strong>{{$permiso->hora_desde}}  <strong> a </strong>{{$permiso->hora_hasta}}</FONT></FONT></P>
				
				<P STYLE="margin-bottom: 0in"><FONT FACE="Arial, serif"><FONT SIZE=3><strong>Motivo: </strong>{{$permiso->motivo}}</FONT></FONT></P>
				
				<P STYLE="margin-bottom: 0in"><FONT FACE="Arial, serif"><FONT SIZE=3><strong>Descripcion: </strong>{{$permiso->descripcion}}</FONT></FONT></P>
				
				<P STYLE="margin-bottom: 0in"><FONT FACE="Arial, serif"><FONT SIZE=3><strong>Solicitante: </strong>{{$permiso->nombre_autorizado.' '.$permiso->apellido_autorizado}}</FONT></FONT></P>
				
				<P STYLE="margin-bottom: 0in"><FONT FACE="Arial, serif"><FONT SIZE=3><strong>Area: </strong>{{$permiso->area}}</FONT></FONT></P>
				
				<P STYLE="margin-bottom: 0in"><FONT FACE="Arial, serif"><FONT SIZE=3><strong>Autorizado por: </strong> {{$jefe->nombre_p.' '.$jefe->apellido}}</FONT></FONT>
				</P>
				<P><BR>
				</P>
			</TD>
		</TR>
	</TABLE>
	<P STYLE="margin-bottom: 0.14in"><BR><BR>
	</P>
</BODY>
</HTML>