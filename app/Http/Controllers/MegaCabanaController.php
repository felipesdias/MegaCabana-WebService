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
}
