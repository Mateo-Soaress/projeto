<?php
    class ProdutoRepositorio {

        private PDO $pdo;

        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
        }

        public function listar(): array {
            $sql = "SELECT * FROM produtos";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $produtos = [];
            while ($row = $stmt->fetch()) {
                $produtos[] = new Produto(
                    $row['id'],
                    $row['nome'],
                    $row['descricao'],
                    $row['preco'],
                    $row['categoria_id']
                );
            }
            return $produtos;
        }

    }
?>