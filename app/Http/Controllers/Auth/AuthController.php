<?php

namespace App\Http\Controllers\Auth;

use DB;
use Mail;
use Auth;
use JWTAuth;
use Socialite;
use App\Mail\ConfirmaEmail;
use App\Model\Perfil\ArvoreGenealogica;
use App\Model\Usuario\User;
use App\Model\Perfil\Perfil;
use App\Model\Usuario\HistLoginUsers;
use App\Model\RegistroSaude\RegistroSaude;
use App\Model\Usuario\TokenFcm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    /**
     * Função para logar o usuário no sistema
     * @param  Request $request identificação
     * @return Dados do usuário
     */
    public function login(Request $request)
    {
        $messages = [
                'password.required' => 'A senha é obrigatória',
                'password.min' => 'A senha deve conter no mínimo 6 digitos'
        ];

        $this->validate($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], $messages);

        try{
            $credentials = $request->only('email', 'password');

            try {
                // verify the credentials and create a token for the user
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->error('Dados inválidos', 401);
                }
            } catch (\JWTException $e) {
                return response()->error('Não foi possivel criar o token', 500);
            }


            $user = Auth::user();

            if($user->email_verified == 0)
                return response()->error('Por favor verifique seu email', 422);

            $fcm = TokenFcm::where('user_id', $user->id)->get(['token']);
            $primeiroLogin = User::primeiroLogin($user->id);

            HistLoginUsers::create(
                [
                    "user_id" => $user->id,
                    "ip" => $_SERVER['REMOTE_ADDR'],
                    "plataforma" => $_SERVER['HTTP_USER_AGENT']
                ]
            );

            return response()->success(compact('user', 'token', 'fcm','primeiroLogin'));
        } catch (\Throwable $e) {
            return $this->erro($e);
        }         
    }

    /**
     * Função para logar com o facebook pelo android
     * @param  Request $request email e token do facebook
     * @return Dados do Usuario e token
     */
    public function loginSocial(Request $request){
        $this->validate($request->all(), [
            'token'             =>      'required|string'
        ]);

        try {
            $userFacebook = Socialite::driver('facebook')
                ->fields(['email'])
                ->scopes(['email'])
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->userFromToken($request->token);

            if(!$userFacebook)
                return response()->error('Este token é inválido.', 422); 

            $user = User::whereEmail($userFacebook->email)->first();

            if(!$user)
                return response()->error("Usuário nao cadastrado", 422);

            $token = JWTAuth::fromUser($user);
            $fcm = TokenFcm::where('user_id', $user->id)->get(['token']);
            $primeiroLogin = User::primeiroLogin($user->id);

            HistLoginUsers::create(
                [
                    "user_id" => $user->id,
                    "ip" => $_SERVER['REMOTE_ADDR'],
                    "plataforma" => $_SERVER['HTTP_USER_AGENT']
                ]
            );

            return response()->success(compact('user', 'token','fcm','primeiroLogin'));
        }  catch (\Throwable $e) {
            return $this->erro($e);
        }
    }

    /**
     * Função para cadastrar um novo usuário
     * @param  Request $request dados do perfil e da conta
     * @return Dados do usuário
     */
    public function register(Request $request)
    {

        // Faz as validações do formulario
        $this->validate($request->all(), [
            'nome'                  => 'required|min:2|max:100',
            'sobrenome'             => 'required|min:2|max:155',
            'nascimento'            => 'required|date_format:"Y-m-d"|size:10',
            'sexo'                  => 'required|boolean',
            'email'                 => 'required|email|unique:user',
            'celular'               => 'required|min:10|max:15',
            'senha'                 => 'required|min:6|confirmed',
        ]);

        $verificationCode = str_random(40);

        DB::beginTransaction();
        try {
            //Criar perfil no banco
            do {
                $cartao_medyes = "".rand(1,3).rand(0,9);
                for($i=2; $i<10; $i++) {
                    $num = rand(0,9);
                    while($cartao_medyes[$i-2] == $cartao_medyes[$i-1] && $cartao_medyes[$i-1] == $num)
                        $num = rand(0,9);
        
                    $cartao_medyes .= $num;
                }
            } while(Perfil::where('id_medyes', intval($cartao_medyes))->first());

            //Cria usuario no banco
            $user = new User;
            $user->email = trim(strtolower($request->email));
            $user->email_verified = 1;
            $user->password = bcrypt($request->senha);
            $user->email_verification_code = $verificationCode;
            $user->save();

            $perfil = new Perfil;
            $perfil->user_id = $user->id;
            $perfil->nome = ucwords(trim($request->nome));
            $perfil->sobrenome = ucwords(trim($request->sobrenome));
            $perfil->id_medyes = intval($cartao_medyes);
            $perfil->nascimento = $request->nascimento;
            $perfil->sexo = $request->sexo;
            $perfil->celular = $request->celular;
            $perfil->save();

            $arvore = new ArvoreGenealogica;
            $arvore->perfil_id = $perfil->id;
            $arvore->save();

            $registro_saude = new RegistroSaude;
            $registro_saude->id = $perfil->id;
            $registro_saude->save();

            DB::commit();

            //Gera token de autenticação
            $token = JWTAuth::fromUser($user);

            //Mail::to($user->email)
            //        ->send(new ConfirmaEmail($user->email, $user->email_verification_code));

            // Mail::send('emails.userverification', ['email' => $request->email, 'verificationCode' => $verificationCode], function ($m) use ($request) {
            //     $m->to($request->email, 'test')->subject('Confirmação de email');
            // });

            //Retorna a response
            return response()->success(compact('user', 'token')); 
        } catch (\Throwable $e) {
            DB::rollback();
            return $this->erro($e);
        }     
    }


    /**
     * Fnução para adicionar uma foto ao cadastrar no MedYes
     * @param  Request $request id e avatar a ser adicionado
     * @return boolean true para sucesso e false para falha
     */
    public function setFotoCadastro(Request $request){
        $this->validate($request->all(), [
            'avatar'    =>      'required|string'
        ]);

        try {
            $perfil = $request->user()->perfil;

            if(!is_null($request->avatar) && !empty($request->avatar) ){

                $diretorio = strrev($perfil->id).$perfil->id.strrev($perfil->id)."/avatar/";

                $foto = $this->save_base64($request->avatar, "", $diretorio);

                if($perfil) {
                    $perfil->update(['avatar' => $foto]);
                    return response()->success($foto);
                }
                else 
                    return response()->error("Perfil não encontrado", 422);
            }else
                return response()->error("Foto inválida", 422);
        } catch (\Throwable $e) {
            return $this->erro($e);
        }

    }

    /**
     * Função para verificar o email do usuário
     * @param  String $email            
     * @param  String $verificationCode 
     * @return Página                   
     */
    public function verifyUserEmail($email, $verificationCode) {
        $user = User::whereEmail($email)->first();

        if(!$user && $user->email_verification_code != $verificationCode)
            return redirect('/#!/userverification/failed');

        $user->email_verified = true;
        $user->save();

        return redirect('/#!/userverification/success');
    }
}
