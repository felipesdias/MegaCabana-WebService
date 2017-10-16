<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nome;
use App\Jogo;
use App\Jogada;
use App\Sorteio;

class MegaCabanaController extends Controller
{
    public function getJogo(Request $request, $id) {
        $data = Jogo::find($id);
        if(! $data)
            return response()->success([]);
        $data->jogadas;
        $data->sorteios;
        return response()->success($data);
    }

    public function getJogos(Request $request, $tipo) {
        $data = Jogo::where('tipo', $tipo)->orderBy('created_at', 'desc')->get();
        return response()->success($data);
    }

    public function criarJogo(Request $request) {
        $message = [
            'tipo.required' => 'Tipo é obrigatório',
            'nome.required' => 'Nome do jogo é obrigatório',
            'nome.min' => 'Nome do jogo deve ter no mínimo 2 letras',
            'qt_numeros.required' => 'Quantidade de números é obrigatório',
            'qt_numeros.between' => 'Quantidade de números deve ser entre 1 e 20'
        ];

        $this->validate($request->all(),[
            'tipo'          =>  'required|numeric|min:0|max:1',
            'nome'          =>  'required|string|min:2',
            'qt_numeros'    =>  'required|numeric|between:1,20'
        ], $message);

        $novo = Jogo::create($request->only('tipo', 'nome', 'qt_numeros'));

        return response()->success($novo);
    }

    public function deletaJogada(Request $request, $id) {
        Jogada::where('id', $id)->delete();
        return response()->success(true);
    }

    public function attJogada(Request $request) {
        $message = [
            'numero.required' => 'Número é obrigatório',
            'numero.min' => 'Número deve ser no mínimo 1',
            'nome.required' => 'Nome do jogador é obrigatório'
        ];

        $this->validate($request->all(),[
            'numero'    =>  'required|numeric|min:1',
            'nome'      =>  'required|string|min:2'
        ], $message);

        $dados = $request->all();

        $anterior = 0;
        foreach($dados as $key => $valor) {
            if($key[0] == "_") {
                if(!$valor || $valor == '')
                    return response()->error('Todos os números são obrigatórios', 422);

                if($valor < 1 || $valor > 60)
                    return response()->error('Todos os números devem ser entre 1 a 60', 422);

                if($valor <= $anterior)
                    return response()->error('Números não estão em sequencia', 422);

                $anterior = $valor;
            }
        }

        $novo = Jogada::find($request->id);

        foreach($dados as $key => $valor) {
            $novo[$key] = $valor;
        }
        $novo->save();

        return response()->success($novo);
    }

    public function addJogador(Request $request) {
        $message = [
            'numero.required' => 'Número é obrigatório',
            'numero.min' => 'Número deve ser no mínimo 1',
            'nome.required' => 'Nome do jogador é obrigatório'
        ];

        $this->validate($request->all(),[
            'numero'    =>  'required|numeric|min:1',
            'nome'      =>  'required|string|min:2'
        ], $message);

        if(Jogada::where('jogo_id', $request->jogo_id)->where('numero', $request->numero)->first())
            return response()->error('Já exite outro jogador com este número', 422);

        $dados = $request->all();

        $anterior = 0;
        foreach($dados as $key => $valor) {
            if($key[0] == "_") {
                if(!$valor || $valor == '')
                    return response()->error('Todos os números são obrigatórios', 422);

                if($valor < 1 || $valor > 60)
                    return response()->error('Todos os números devem ser entre 1 a 60', 422);

                if($valor <= $anterior)
                    return response()->error('Números não estão em sequencia', 422);

                $anterior = $valor;
            }
        }

        $novo = new Jogada;
        
        foreach($dados as $key => $valor) {
            $novo[$key] = $valor;
        }
        $novo->save();

        return response()->success($novo);
    }

    public function addSorteio(Request $request) {
        $dados = $request->all();

        $anterior = 0;
        foreach($dados as $key => $valor) {
            if($key[0] == "_") {
                if(!$valor || $valor == '')
                    return response()->error('Todos os números são obrigatórios', 422);

                if($valor < 1 || $valor > 60)
                    return response()->error('Todos os números devem ser entre 1 a 60', 422);

                if($valor <= $anterior)
                    return response()->error('Números não estão em sequencia', 422);

                $anterior = $valor;
            }
        }

        $novo = new Sorteio;

        foreach($dados as $key => $valor) {
            $novo[$key] = $valor;
        }
        $novo->save();

        return response()->success($novo);
    }

    public function attSorteio(Request $request) {
        $dados = $request->all();

        $anterior = 0;
        foreach($dados as $key => $valor) {
            if($key[0] == "_") {
                if(!$valor || $valor == '')
                    return response()->error('Todos os números são obrigatórios', 422);

                if($valor < 1 || $valor > 60)
                    return response()->error('Todos os números devem ser entre 1 a 60', 422);

                if($valor <= $anterior)
                    return response()->error('Números não estão em sequencia', 422);

                $anterior = $valor;
            }
        }

        $novo = Sorteio::find($request->id);

        foreach($dados as $key => $valor) {
            $novo[$key] = $valor;
        }
        
        $novo->save();

        return response()->success($novo);
    }

    public function deletaSorteio(Request $request, $id) {
        Sorteio::where('id', $id)->delete();
        return response()->success(true);
    }

    public function deletaJogo(Request $request, $id) {
        Jogo::where('id', $id)->delete();
        return response()->success(true);
    }
}
