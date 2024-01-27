<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class toDoListResource extends JsonResource
{
   public $statusCode;
   public $message;
   public $resource;

   public function __construct($statusCode, $message, $resource){

    parent::__construct($resource);
    $this->statusCode = $statusCode;
    $this->message = $message;
   }

   public function toArray($request){

    return [
        'status'   => $this->statusCode,
        'message'   => $this->message,
        'data'      => $this->resource
    ];
   }
}
