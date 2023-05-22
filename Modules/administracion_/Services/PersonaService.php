<?php


namespace Modules\administracion_\Services;


use App\Services\Services;

class PersonaService extends Services
{

 public function __construct()
  {
      parent::__construct('Modules\administracion_\Models\Persona');
   }

}

