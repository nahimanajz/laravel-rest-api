<?php
namespace Helpers\Classes;
 class SuccessMessage {
    public function __construct($model, $action, $success ='is successfull'){
        return $model.$action.$success;
    }
} 
?>