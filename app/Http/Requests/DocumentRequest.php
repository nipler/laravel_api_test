<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;


class DocumentRequest extends FormRequest
{
	public $validator = null;
	
	public function wantsJson()
    {
        return true;
    }
	
	/**
     * Переопределяем метод
     *
     * @return bool
     */
	protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator) {
		$this->validator = $validator;
	}
	
	
    /**
     * Просим отдаватьвсе в json
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->isJson();
    }

    /**
     * Устанавливаем правила валидации
     *
     * @return array
     */
    public function rules(Request $request)  {
		
        $rules = [
			// 'id' => ['required', 'string', 'uuid'],
			// 'payload' => ['required'],
        ];
		
		switch ($this->getMethod())
		{
			case 'POST':
				/* $segments = $this->segments();

				if(isset($segments[4]) && $segments[4] == "publish") {
					
				} */
				
				break;
			case 'PATCH':
				$rules = [
					'payload' => ['required'],
				];
				break;
		} 
		
		return $rules;
    }
	
	/**
     * Устанавливаем сообщения при ошибке
     *
     * @return array
     */
	public function messages() {
        return [
            'id.uuid' => 'ID невалидный uuid',
            'id.string' => 'ID не является строкой',
            'id.required' => 'ID не может быть пустым',
            'payload.required' => 'Payload не может быть пустым',
        ];
    }
	
	/**
     * Преобразуем json запрос в нужный формат
     *
     * @return array
     */
	public function all($keys = null){
	
		$json = parent::json()->all();

		if(!isset($json['document'])) return [];
			
        if(empty($keys)){
			return $json['document'];
        }
		
        return collect($json['document'])->only($keys)->toArray();
    }
}
