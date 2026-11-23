<?php
require_once __DIR__ . '/Repositorio.php';

class Pet extends Repositorio
{
    protected string $tabela = 'pets';
    protected array $campos = ['cliente_id', 'nome', 'especie', 'raca', 'nascimento', 'observacoes'];
    protected array $obrigatorios = ['cliente_id', 'nome', 'especie'];

    // Lista já com o nome do tutor (JOIN)
    public function listar(string $busca = ''): array
    {
        $sql = 'SELECT p.*, c.nome AS tutor
                FROM pets p JOIN clientes c ON c.id = p.cliente_id
                ORDER BY p.id DESC';
        $linhas = $this->db->query($sql)->fetchAll();
        if ($busca !== '') {
            $linhas = array_values(array_filter($linhas, function ($l) use ($busca) {
                return stripos(implode(' ', $l), $busca) !== false;
            }));
        }
        return $linhas;
    }
}
