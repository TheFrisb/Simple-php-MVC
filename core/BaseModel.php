<?php

namespace Core;

abstract class BaseModel // nedava instanca
{
    const RULE_REQUIRED = 'required';
    const RULE_STRING = 'string';
    const RULE_INTEGER = 'integer';

    public array $errors = [];


    public function loadData($data) : void {
        foreach ($data as $key => $value) {
            if(property_exists($this, $key)){
                $this->{$key} = $value;
            }

        }
    }

    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($rule)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorByRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_STRING && !is_string($value)) {
                    $this->addErrorByRule($attribute, self::RULE_STRING);
                }
                if ($ruleName === self::RULE_INTEGER && !is_int($value)) { // What's the difference between is_int and is_integer ??
                    $this->addErrorByRule($attribute, self::RULE_INTEGER);
                }


            }
        }

        return empty($this->errors);
    }

    public function errorMessages(): array
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_STRING => 'This field must be a string',
            self::RULE_INTEGER => 'This field must be an integer',
        ];
    }

    public function errorMessage($rule): string
    {
        return $this->errorMessages()[$rule];
    }

    protected function addErrorByRule(string $attribute, string $rule, $params = []): void
    {
        $params['field'] ??= $attribute;
        $errorMessage = $this->errorMessage($rule);
        foreach ($params as $key => $value) {
            $errorMessage = str_replace("{{$key}}", $value, $errorMessage);
        }
        $this->errors[$attribute][] = $errorMessage;
    }

    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }



    abstract public function getRequiredFieldsWithRules();
}