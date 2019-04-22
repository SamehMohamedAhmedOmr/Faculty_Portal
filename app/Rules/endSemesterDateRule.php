<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class endSemesterDateRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $type;
    protected $Start_Date;

    protected $checkDay = 0;
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
            $expectedValue =  Carbon::parse($this->Start_Date)->addMonth(3)->format('Y-m-d');
            if(strtotime($value) < strtotime($expectedValue)){
                return false;
            }
            else{
                $endDate = Carbon::parse($value);
                if($endDate->dayOfWeek != 4){ // Not Equal thursday الخميس
                    $this->checkDay = 1;
                    return false;
                }
                return true;
            }

        }
        elseif($this->type == 1){
            $expectedValue =  Carbon::parse($this->Start_Date)->addMonth(2)->format('Y-m-d');
            if(strtotime($value) < strtotime($expectedValue)){
                return false;
            }
            else{
                $endDate = Carbon::parse($value);
                if($endDate->dayOfWeek != 4){ // Not Equal thursday الخميس
                    $this->checkDay = 1;
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
        if($this->checkDay == 0){
            if($this->type == 0){
                return 'The :attribute of Semester must be after 3 Months.';
            }
            elseif($this->type == 1){
                return 'The :attribute of Semester must be after 90 Days.';
            }
        }
        elseif($this->checkDay == 1){
            return 'The :attribute of Semester must be end by Thursday';
        }
        return null;
    }
}
