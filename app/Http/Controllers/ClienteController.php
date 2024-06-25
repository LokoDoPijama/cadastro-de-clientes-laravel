<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Cliente;

class ClienteController extends Controller
{
    public function cadastrar(Request $request) {
        $cliente = new Cliente;
        $cliente->nome = $request->nome;
        $cliente->dataNasc = $request->dataNasc;
        $cliente->cep = $request->cep;

        $response = Http::get('https://viacep.com.br/ws/' . $request->cep . '/json')->json();

        $cliente->endereco = $response['logradouro'];
        $cliente->numero = $response['complemento'];
        $cliente->bairro = $response['bairro'];
        $cliente->cidade = $response['localidade'];
        $cliente->uf = $response['uf'];
        
        $cliente->save();

        return redirect('/');
    }

    public function editar(Request $request) {
        $cliente = Cliente::find($request->codigo);
        $cliente->nome = $request->nome;
        $cliente->dataNasc = $request->dataNasc;
        $cliente->cep = $request->cep;

        $response = Http::get('https://viacep.com.br/ws/' . $request->cep . '/json')->json();

        $cliente->endereco = $response['logradouro'];
        $cliente->numero = $response['complemento'];
        $cliente->bairro = $response['bairro'];
        $cliente->cidade = $response['localidade'];
        $cliente->uf = $response['uf'];

        $cliente->save();

        return redirect('/');
    }

    public function deletar(Request $request) {
        $cliente = Cliente::find($request->codigo);
        $cliente->delete();

        return redirect('/');
    }

}
