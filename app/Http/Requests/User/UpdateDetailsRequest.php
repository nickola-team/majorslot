<?php 
namespace VanguardLTE\Http\Requests\User
{
    class UpdateDetailsRequest extends \VanguardLTE\Http\Requests\Request
    {
        public function rules()
        {
            return [
                'password' => 'confirmed', 
                'confirmation_token' => 'confirmed', 
            ];
        }
    }

}
