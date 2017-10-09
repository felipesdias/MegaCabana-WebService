<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Função para retornar o erro do banco
     * @param  \Throwable $e 
     * @return mensagem de erro        
    */
    public function erro(\Throwable $e){
    	if(config('app.debug'))
			return $e->getMessage();

        return response()->error('Erro interno do servidor',500);
    }

    /**
     * Função para verificar se o usuario tem permissão
     * @param  $id     a ser comparado
     * @param  $perfil que está fazendo a solicitação
     * @return Boolean
     */
    public function verificaPermissao($id, $perfil){
        return ($perfil->id == $id || $perfil->dependentes->contains($id));
    }
    

    /**
     * Corrige redicionamento quando a validação falha
     *
     * @param Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return void
     */
    public function validate(array $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request, $rules, $messages, $customAttributes);
        if($validator->fails()) {
            $message = $validator->errors()->first();
            throw new ValidationException($validator);
        }
    }

    /**
     * Filtra os dados do array que não estão setados e string vazias
     *
     * @param array $array
     * @return void
     */
    public function filtraArray(array $array) {
        return array_filter($array, function($elemento) {
            if(!isset($elemento) || $elemento == "")
                return false;
            else
                return true;
        });
    }

    /**
     * Salva arquivo no disco
     *
     * @param [type] $base64_string
     * @param [type] $output_file
     * @param [type] $local
     * @return void
     */
    public function save_base64($base64_string, $output_file, $local) {
        $data = explode( ',', $base64_string );
        $formato = explode(';', explode('/', $data[0])[1])[0];

        $arquivo = str_replace([" ", ":"], "_", $local.$output_file."_".Carbon::now()."_".str_random(15).".".$formato);

        Storage::put($arquivo, base64_decode($data[1]), 'public');

        return Storage::url($arquivo);
    }
}
