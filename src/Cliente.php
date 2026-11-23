<?php
require_once __DIR__ . '/Repositorio.php';

class Cliente extends Repositorio
{
    protected string $tabela = 'clientes';
    protected array $campos = ['nome', 'telefone', 'email', 'cep', 'logradouro', 'bairro', 'cidade', 'uf'];
    protected array $obrigatorios = ['nome'];
}
