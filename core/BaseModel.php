<?php

namespace Core;

/*
 * The BaseModel class, currently only loading data into the model via a method.
 *
 */
abstract class BaseModel // nedava instanca
{
    public array $errors = [];


    public function loadData($data) : void {
        foreach ($data as $key => $value) {
            if(property_exists($this, $key)){
                $this->{$key} = $value;
            }
        }
    }

}