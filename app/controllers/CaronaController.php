<?php
/**
 * Controlador de Caronas
 * Responsável por gerenciar ações relacionadas a caronas
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Carona.php';

class CaronaController {
    private $caronaModel;
    
    public function __construct() {
        global $conn;
        $this->caronaModel = new Carona($conn);
    }
    
    /**
     * Listar todas as caronas disponíveis
     */
    public function listar() {
        return $this->caronaModel->listar();
    }
    
    /**
     * Buscar caronas por origem e destino
     */
    public function buscar($origem, $destino) {
        if (empty($origem) && empty($destino)) {
            return $this->listar();
        }
        
        return $this->caronaModel->buscar($origem, $destino);
    }
    
    /**
     * Obter detalhes de uma carona
     */
    public function obterDetalhes($id) {
        if (empty($id) || !is_numeric($id)) {
            return null;
        }
        
        return $this->caronaModel->buscarPorId($id);
    }
    
    /**
     * Criar nova carona
     */
    public function criar($usuario_id, $origem, $destino, $data_saida, $hora_saida, $vagas, $descricao) {
        // Validações
        if (empty($usuario_id) || empty($origem) || empty($destino) || empty($data_saida) || empty($hora_saida) || empty($vagas)) {
            return ['sucesso' => false, 'mensagem' => 'Todos os campos são obrigatórios'];
        }
        
        if ($vagas < 1 || $vagas > 10) {
            return ['sucesso' => false, 'mensagem' => 'Número de vagas inválido'];
        }
        
        // Validar data
        $data_obj = DateTime::createFromFormat('Y-m-d', $data_saida);
        if (!$data_obj || $data_obj->format('Y-m-d') !== $data_saida) {
            return ['sucesso' => false, 'mensagem' => 'Data inválida'];
        }
        
        if ($data_obj < new DateTime()) {
            return ['sucesso' => false, 'mensagem' => 'A data da carona não pode ser no passado'];
        }
        
        // Criar carona
        $this->caronaModel->usuario_id = $usuario_id;
        $this->caronaModel->origem = $origem;
        $this->caronaModel->destino = $destino;
        $this->caronaModel->data_saida = $data_saida;
        $this->caronaModel->hora_saida = $hora_saida;
        $this->caronaModel->vagas_disponiveis = $vagas;
        $this->caronaModel->descricao = $descricao;
        
        if ($this->caronaModel->criar()) {
            return ['sucesso' => true, 'mensagem' => 'Carona criada com sucesso'];
        } else {
            return ['sucesso' => false, 'mensagem' => 'Erro ao criar carona'];
        }
    }
    
    /**
     * Atualizar carona
     */
    public function atualizar($id, $origem, $destino, $data_saida, $hora_saida, $vagas, $descricao) {
        if (empty($id)) {
            return ['sucesso' => false, 'mensagem' => 'ID da carona inválido'];
        }
        
        $this->caronaModel->id = $id;
        $this->caronaModel->origem = $origem;
        $this->caronaModel->destino = $destino;
        $this->caronaModel->data_saida = $data_saida;
        $this->caronaModel->hora_saida = $hora_saida;
        $this->caronaModel->vagas_disponiveis = $vagas;
        $this->caronaModel->descricao = $descricao;
        
        if ($this->caronaModel->atualizar()) {
            return ['sucesso' => true, 'mensagem' => 'Carona atualizada com sucesso'];
        } else {
            return ['sucesso' => false, 'mensagem' => 'Erro ao atualizar carona'];
        }
    }
    
    /**
     * Deletar carona
     */
    public function deletar($id) {
        if (empty($id)) {
            return ['sucesso' => false, 'mensagem' => 'ID da carona inválido'];
        }
        
        if ($this->caronaModel->deletar($id)) {
            return ['sucesso' => true, 'mensagem' => 'Carona deletada com sucesso'];
        } else {
            return ['sucesso' => false, 'mensagem' => 'Erro ao deletar carona'];
        }
    }
}
?>
