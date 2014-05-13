<?php namespace Agency\Cms\Validators;

use Agency\Cms\Exceptions\InvalidPermissionException;

use Agency\Validators\Validator;

class PermissionValidator extends Validator implements Contracts\PermissionValidatorInterface {

    protected $rules = [
        'title'       => 'required|max:255',
        'alias'       => 'required|alias|alpha_dash|max:255',
        'description' => 'max:1000'
    ];

    public function validate($attributes)
    {
        $this->validator->extend('alias', function($attributes, $value, $parameters) {
            return ! preg_match('/[A-Z]/', $value);
        });

        $validation = $this->validation($attributes);

        if ($validation->fails())
        {
            throw new InvalidPermissionException($validation->messages()->all());
        }

        return true;
    }
}