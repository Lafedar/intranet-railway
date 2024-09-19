<?php

namespace App;
use DB;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Validation\ValidationException;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    
    // Campos que pueden ser llenados masivamente
    protected $fillable = [
        'sala',
        'titulo',
        'descripcion',
        'pedido_por',
        'color',
        'textColor',
        'start',
        'end',
    ];

    public static function createEvento(array $data)
    {
        $validatedData = self::validate($data);
        return self::create($validatedData);
    }

    public function updateEvento(array $data)
    {
        $validatedData = self::validate($data);
        $this->update($validatedData);
        return $this;
    }

    public static function validate(array $data)
    {
        $rules = [
            'sala'       => 'required|string|max:50',
            'titulo'        => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'pedido_por'  => 'required|string|max:255',
            'color'       => 'required|string',
            'textColor'   => 'required|string',
            'start'       => 'required',
            'end'         => 'required'
        ];
    
        
        $validator = Validator::make($data, $rules);
    
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
        
    }

    public function deleteEvento()
    {
        $this->delete();
    }

    public static function getPersons()
    {
        return DB::table('personas')->orderBy('nombre_p', 'asc')->get();
    }

    public static function getSalas()
    {
        return DB::table('salas')->orderBy('id', 'asc')->get();
    }
}
