<?php
    class ProdutoRepositorio {

        private PDO $pdo;

        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
        }

        public function formarObjeto(array $dados): Produto {
            return new Produto($dados['id'], $dados['nome'], $dados['descricao'], $dados['preco'], $dados['categoria_id']);
        }

        public function listar(): array {
            $sql = "SELECT * FROM produtos";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $produtos = [];
            while ($row = $stmt->fetch()) {
                $produtos[] = $this->formarObjeto($row);
            }
            return $produtos;
        }

        public function salvar(Produto $produto): void {
            $sql = "INSERT INTO produtos (nome, descricao, preco, categoria_id) VALUES (:nome, :descricao, :preco, :categoria_id)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $produto->getNome());
            $stmt->bindValue(':descricao', $produto->getDescricao());
            $stmt->bindValue(':preco', $produto->getPreco());
            $stmt->bindValue(':categoria_id', $produto->getCategoriaId());
            $stmt->execute();
        }

    }
?>