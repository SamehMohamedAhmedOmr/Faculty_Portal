<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class midtermDateRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $type,$Start_Date,$midterm;
    protected $checkMidterm=0;
    public function __construct($type,$Start_Date)
    {
        $this->type=$type;
        $this->Start_Date=$Start_Date;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($this->type == 0){
            $expectedValue =  Carbon::parse($this->Start_Date)->addWeek(7)->format('Y-m-d');
            if(strtotime($value) < strtotime($expectedValue)){
                return false;
            }
            else{
                $this->midterm = Carbon::parse($value);
                if($this->midterm->dayOfWeek != 0){ // Not Equal sunday الاحد
                    $this->checkMidterm = 1;
                    return false;
                }
                return true;
            }

        }
        elseif($this->type == 1){
            $expectedValue =  Carbon::parse($this->Start_Date)->addWeek(5)->format('Y-m-d');
            if(strtotime($value) < strtotime($expectedValue)){
                return false;
            }
            else{
                $this->midterm = Carbon::parse($value);
                if($this->midterm->dayOfWeek != 0){ // Not Equal sunday الاحد
                    $this->checkMidterm = 1;
                    return false;
                }
                return true;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->checkMidterm === 0){
            if($this->type == 0){
                return 'The :attribute should start in at least week 7.';
            }
            elseif($this->type == 1){
                return 'The :attribute should start in at least week 5.';
            }
        }
        elseif($this->checkMidterm === 1){
            return 'The :attribute should start on Sunday.';
        }
        return null;
    }
}
