<?php
require_once __DIR__ . '/Database.php';

// Classe base do CRUD: cada entidade só informa tabela e campos.
abstract class Repositorio
{
    protected string $tabela;
    protected array $campos = [];       // campos editáveis
    protected array $obrigatorios = []; // campos que não podem ficar vazios

    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function listar(string $busca = ''): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->tabela} ORDER BY id DESC");
        $linhas = $stmt->fetchAll();
        if ($busca !== '') {
            $linhas = array_values(array_filter($linhas, function ($l) use ($busca) {
                return stripos(implode(' ', $l), $busca) !== false;
            }));
        }
        return $linhas;
    }

    public function buscar(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tabela} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    // Insere (sem id) ou atualiza (com id). Retorna o id.
    public function salvar(array $dados): int
    {
        $valores = [];
        foreach ($this->campos as $c) {
            $v = isset($dados[$c]) ? trim((string)$dados[$c]) : '';
            if ($v === '' && in_array($c, $this->obrigatorios, true)) {
                throw new InvalidArgumentException("O campo '$c' é obrigatório.");
            }
            $valores[$c] = ($v === '') ? null : $v;
        }

        $id = (int)($dados['id'] ?? 0);
        if ($id > 0) {
            $set = implode(', ', array_map(fn($c) => "$c = :$c", $this->campos));
            $valores['id'] = $id;
            $this->db->prepare("UPDATE {$this->tabela} SET $set WHERE id = :id")->execute($valores);
            return $id;
        }

        $cols = implode(', ', $this->campos);
        $params = implode(', ', array_map(fn($c) => ":$c", $this->campos));
        $this->db->prepare("INSERT INTO {$this->tabela} ($cols) VALUES ($params)")->execute($valores);
        return (int)$this->db->lastInsertId();
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->tabela} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
