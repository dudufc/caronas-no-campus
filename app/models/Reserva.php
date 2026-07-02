<?php

/**

 * Modelo de Reserva

 * Responsável por operações relacionadas a reservas de caronas

 */



class Reserva {

    private $conn;

    private $table = 'reservas';

    

    public $id;

    public $carona_id;

    public $usuario_id;

    public $status;

    public $data_reserva;

    

    public function __construct($db) {

        $this->conn = $db;

    }

    

    /**

     * Buscar reserva por ID

     */

    public function buscarPorId($id) {

        $query = "SELECT r.*, c.origem, c.destino, c.data_saida, c.hora_saida, u.nome as passageiro FROM " . $this->table . " r

                  JOIN caronas c ON r.carona_id = c.id

                  JOIN usuarios u ON r.usuario_id = u.id

                  WHERE r.id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();

    }

    

    /**

     * Listar reservas de um usuário

     */

    public function listarPorUsuario($usuario_id) {

        $query = "SELECT r.*, c.origem, c.destino, c.data_saida, c.hora_saida, u.nome as motorista FROM " . $this->table . " r

                  JOIN caronas c ON r.carona_id = c.id

                  JOIN usuarios u ON c.usuario_id = u.id

                  WHERE r.usuario_id = ?

                  ORDER BY c.data_saida DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $usuario_id);

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    }

    

    /**

     * Listar reservas de uma carona

     */

    public function listarPorCarona($carona_id) {

        $query = "SELECT r.*, u.nome as passageiro, u.telefone FROM " . $this->table . " r

                  JOIN usuarios u ON r.usuario_id = u.id

                  WHERE r.carona_id = ?

                  ORDER BY r.data_reserva DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $carona_id);

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    }

    

    /**

     * Criar nova reserva

     */

    public function criar() {

        $query = "INSERT INTO " . $this->table . " 

                  (carona_id, usuario_id, status, data_reserva) 

                  VALUES (?, ?, 'pendente', NOW())";

        

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ii", $this->carona_id, $this->usuario_id);

        

        return $stmt->execute();

    }

    

    /**

     * Atualizar status da reserva

     */

    public function atualizarStatus($id, $status) {

        $query = "UPDATE " . $this->table . " 

                  SET status = ? 

                  WHERE id = ?";

        

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("si", $status, $id);

        

        return $stmt->execute();

    }

    

    /**

     * Cancelar reserva

     */

    public function cancelar($id) {

        $query = "DELETE FROM " . $this->table . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $id);

        return $stmt->execute();

    }

    

    /**

     * Verificar se usuário já tem reserva para uma carona

     */

    public function verificarReservaExistente($carona_id, $usuario_id) {

        $query = "SELECT id FROM " . $this->table . " 

                  WHERE carona_id = ? AND usuario_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ii", $carona_id, $usuario_id);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();

    }

}

?>
