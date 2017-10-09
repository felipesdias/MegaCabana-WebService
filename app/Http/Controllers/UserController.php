<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Model\Usuario\User;
use Hash;
use App\Model\Usuario\TokenFcm;
use DB;

class UserController extends Controller
{
    
    /**
     * Função para atualizar os dados da conta do usuário
     * @param  Request $request dados a serem atualizados
     * @return Boolean
     */
	public function updateUser(Request $request) {
		$user = $request->user();

		$this->validate($request->all(), [
			'email'          	=> 'email'|Rule::unique('user')->ignore($user->id),
			'senha_atual'    	=> 'required|min:6',
			'senha'       		=> 'min:6|confirmed'
        ]);

		//Verifica se a senha atual é confere
		if(!Hash::check($request->senha_atual,$user->password))
			return response()->error('Senha atual não confere', 422);
			
		//Verifica se a senha atual é igual a antiga
		if($request->has('password')) {
			if($request->senha_atual == $request->password)
				return response()->error('Nova senha não pode ser igual a antiga', 422);
			else {
				$user->password = bcrypt($request->password);
			}
		}

		if($request->has('email'))
			$user->email = $request->email;

        try{
	        $user->save();
	        return response()->success(true);
        }catch (\Throwable $e) {
            return $this->erro($e);
        }   
	}

	/**
	 * Função para adicionar um token fcm para o usuário
	 * @param  Request $request token fcm
	 * @return Boolean
	 */
    public function setTokenFcm(Request $request){
        $this->validate($request->all(),[
            'token'     =>      'required|string'
        ]);

        DB::beginTransaction();
        try{
        	$user = $request->user();

	        $existeToken = TokenFcm::where('token',$request->token)->first();

	        if($existeToken){
	        	if($existeToken->user_id != $user->id){
	        		TokenFcm::where('token',$request->token)->delete();
	        		TokenFcm::create(['user_id' => $user->id, 'token' => $request->token]);
	        	}
	        }else
				TokenFcm::create(['user_id' => $user->id, 'token' => $request->token]);

	        DB::commit();

	        return response()->success(true);
        }catch (\Throwable $e) {
            DB::rollback();
            return $this->erro($e);
        }   
    }
}
