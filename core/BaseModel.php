<?php

namespace Core;

abstract class BaseModel // nedava instanca
{
    public function loadData($data) : void {
        foreach ($data as $key => $value) {
            if(property_exists($this, $key)){
                $this->{$key} = $value;
            }
        }
    }
//    public function validate()
//    {
//        foreach ($this->getRequiredFields() as $field => $required){
//            // pass in model so load only required data // if valid save if not fuck off
//        }
//    }

    abstract public function getRequiredFields();
}