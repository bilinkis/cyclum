<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use \Auth;

class EditUserRequest extends Request
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
        if(Auth::user()->isALeader()){
            return [
                'teamName' => 'required',
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.Auth::user()->id,
                'password' => 'required|min:6',
            ];
        }
        else{
            return [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.Auth::user()->id,
                'password' => 'required|min:6',
            ];
        }
    }
}
