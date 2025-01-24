@extends('plantillas.layout')
<!--includes de la cabecera-->
@section('cabecera')
@stop
<!--includes de la cabecera-->

<!--miga de pan-->
@section('migaPan')   
<h1>TITULO PRINCIPAL<small>Blank example to the fixed layout</small></h1>
 <!--  MIGA DE PAN -->
<ol class="breadcrumb">
  <li><a href="javascript: void(0)"><i class="fa fa-dashboard"></i> Inicio</a></li>
  <li><a href="javascript: void(0)">Capa</a></li>
  <li class="active">Fixed</li>
</ol>
<!--  #MIGA DE PAN -->
@stop
<!--# miga de pan-->

<!--menu lateral izquierdo-->
@section('menuLateral') 
  @include('includes.menuLateral')
@stop
<!-- #menu lateral izquierdo-->

@section('contenido')
  contenido xxxxx 
@stop