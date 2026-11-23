<?php
require_once __DIR__ . '/Auth.php';

// Controlador genérico: recebe um Repositorio e responde em JSON.
class CrudController
{
    public function __construct(private Repositorio $repo) {}

    public function tratar(): void
    {
        Auth::exigirApi();
        header('Content-Type: application/json; charset=utf-8');

        try {
            $acao = $_POST['acao'] ?? $_GET['acao'] ?? 'listar';

            switch ($acao) {
                case 'listar':
                    $resp = ['ok' => true, 'dados' => $this->repo->listar($_GET['busca'] ?? '')];
                    break;
                case 'buscar':
                    $resp = ['ok' => true, 'dados' => $this->repo->buscar((int)($_GET['id'] ?? 0))];
                    break;
                case 'salvar':
                    $id = $this->repo->salvar($_POST);
                    $resp = ['ok' => true, 'id' => $id];
                    break;
                case 'excluir':
                    $resp = ['ok' => $this->repo->excluir((int)($_POST['id'] ?? 0))];
                    break;
                default:
                    http_response_code(400);
                    $resp = ['ok' => false, 'erro' => 'Ação inválida'];
            }
        } catch (InvalidArgumentException $e) {
            http_response_code(422);
            $resp = ['ok' => false, 'erro' => $e->getMessage()];
        } catch (Throwable $e) {
            http_response_code(500);
            $resp = ['ok' => false, 'erro' => 'Erro interno ao processar a requisição.'];
        }

        echo json_encode($resp);
    }
}
