<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{


  public function scopeID($query, $id)
  {
    if ($id) {
      return $query->where('id', 'LIKE', "%$id%");
    }
  }
  public function scopeTitulo($query, $titulo)
  {
    if ($titulo) {
      return $query->where('titulo', 'LIKE', "%$titulo%");
    }
  }

  public function scopeObs($query, $obs)
  {
    if ($obs) {
      return $query->where('obs', 'LIKE', "%$obs%");
    }
  }
  public function scopeVersion($query, $version)
  {
    if ($version) {
      return $query->where('version', 'LIKE', "%$version%");
    }
  }
  public function scopeFecha($query, $fecha)
  {
    if ($fecha != null) {
      return $query->where('fecha', $fecha);
    }
  }
}
