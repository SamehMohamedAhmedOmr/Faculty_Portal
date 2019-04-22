<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'national_id'=>'required|digits:14"',
            'name_ar'=>'required|min:10|max:100|string|regex:"[\p{Arabic}\s]"',
            'name_en'=>'required|min:10|max:100|string|regex:/^[a-zA-Z\s]+$/u',
            'email'=>'required|max:100|email',
            'address'=>'required|max:100|regex:"(?=.*[A-Za-z])[A-Za-z0-9\'\.\-\s\,]{5,}"',
            'phone'=>'required|digits_between:8,13',
            'DOB'=>'required|date|before:today',
            'gender'=>'required|boolean',
        ];
    }

    public function messages()
    {
        return [

            /*required Attributes*/
            'national_id.required' => 'National Id is required',
            'name_ar.required'  => 'Arabic name is required',
            'name_en.required'  => 'English name is required',
            'email.required'  => 'E-mail is required',
            'address.required'  => 'Address is required',
            'DOB.required'  => 'Birth date is required',
            'gender.required'  => 'Gender is required',
            'phone.required'  => 'Phone number is required',
            'phone.required'=> 'phone number is required',

            /*customize Errors */
            'national_id.digits' => 'Please enter valid national id , consist of (14 number)',
            'name_ar.regex'  => 'Please enter valid arabic name',
            'name_en.regex'  => 'Please enter valid english name',
            'name_ar.max'  => 'Please enter valid arabic name with at most 100 letters only',
            'name_en.max'  => 'Please enter valid english name with at most 100 letters only',
            'name_ar.min'  => 'Please enter valid arabic name with at least 10 letters only',
            'name_en.min'  => 'Please enter valid english name with at least 10 letters only',
            'email.email'  => 'Please enter valid E-mail',
            'email.max'  => 'Please enter valid E-mail (Maximum 100 characters)',
            'email.unique'  => 'This email is already exists',
            'address.regex'  => 'Please enter valid address between 5 and 100 characters (contains letters)',
            'address.max'  => 'The address can not be more than 100 characters',
            'phone.digits_between'  => 'Please enter valid phone number',
            'DOB.date'  => 'Please enter valid date',
            'DOB.before'  => 'Please enter valid date of birth',
            'gender.boolean'  => 'Please choose male or female option',
        ];
    }
}
