<?php

namespace App\Exports;

use App\Almuerzo;
//use Illuminate\Contracts\View\View;
//use Maatwebsite\Excel\Concerns\FromView;
//use Maatwebsite\Excel\Concerns\FromColletion;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\facades\Excel;



Class AlmuerzoExport implements FromQuery
{
	use Exportable; //permite usar el metodo download

	private $Day;

	public function forDay($Day)
	{
		$this->Day=$Day;
		return $this;


	}


	

	public function query(){

		return Almuerzo::query()->whereDay('fecha_desde','=',$this->Day);

	}

}




/*
class AlmuerzoExport implements FromView
{
    use Exportable;
    private $date;


   public function collection(){

   	return Almuerzo::all();
   }

   public function view():View
   {

   	return view('almuerzo.index',['almuerzo'=>Almuerzo::get()]);

   }

}*/
