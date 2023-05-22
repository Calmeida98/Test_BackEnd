<?php

namespace Modules\administracion_\Http\Controllers;

use App\Http\Controllers\RestController;

class PersonaController extends RestController
{


    /**
     *  PersonaController constructor.
     */
    public function __construct()
    {
        $classnamespace='Modules\administracion_\Models\Persona';
        $classnamespaceservice='Modules\administracion_\Services\PersonaService';
        $this->modelClass=new $classnamespace ;
        $this->service= new $classnamespaceservice(new $classnamespace);
    }


}

