<?php

namespace App\Filament\Resources\MovimentoResource\Pages;

use App\Filament\Resources\MovimentoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMovimento extends CreateRecord
{
    protected static string $resource = MovimentoResource::class;

    protected function beforeCreate(): void
    {
        // Runs before the form fields are saved to the database.
        $data=$this->data;
        $produto = Produto::find($data['produto_id']);
        $quantidade = (int)$data['quantidade'];
        $tipo = $data['tipo'];

        if ($tipo === 'saída' && $quantidade >$produto->estoque){
            Notificatio::make()
                ->title('Estoque insuficiente')
                ->body("estoque de '{$produto->nome}' é de apenas {$produto->estoque} unidades")
                ->danger()
                ->send();
            $this->halt();
        }
    }

    protected function afterCreate(): void
    {
        $movimento = $this->getRecord();
        $produto = $movimento->produto;
        // Runs after the form fields are saved to the database.
        
        if ($movimento->tipo === 'entrada'){
            $produto->increment('estoque', $movimento->quantidade);
        } else {
            $produto->decrement('estoque', $movimento->quantidade);
        }
    }
}

