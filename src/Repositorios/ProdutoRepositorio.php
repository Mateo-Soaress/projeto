<?php
    class ProdutoRepositorio 
    {

        private PDO $pdo;

        public function __construct(PDO $pdo) 
        {
            $this->pdo = $pdo;
        }

        public function formarObjeto(array $dados): Produto 
        {
            return new Produto($dados['id'], $dados['nome'], $dados['descricao'], $dados['preco'], $dados['categoria_id'], $dados['imagem']);
        }

        public function listar(): array 
        {
            $sql = "SELECT id, nome, descricao, preco, categoria_id, imagem FROM produtos";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $produtos = [];
            while ($row = $stmt->fetch()) {
                $produtos[] = $this->formarObjeto($row);
            }
            return $produtos;
        }

        public function produtos(): array
        {
            $sql1 = "SELECT * FROM produtos ORDER BY preco";
            $statement = $this->pdo->query($sql1);
            $produtosInfo = $statement->fetchAll(PDO::FETCH_ASSOC);

            $dadosInfo = array_map(function ($info) {
                return $this->formarObjeto($info);
            }, $produtosInfo);

            return $dadosInfo;
        }

        public function buscarPorId(int $id): ?Produto
        {
            $sql = "SELECT id, nome, descricao, preco, categoria_id, imagem FROM produtos WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $dados = $stmt->fetch();

            return $dados ? $this->formarObjeto($dados): null;
        }

        public function salvar(Produto $produto): void 
        {
            $sql = "INSERT INTO produtos (nome, descricao, preco, categoria_id, imagem) VALUES (:nome, :descricao, :preco, :categoria_id, :imagem)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $produto->getNome());
            $stmt->bindValue(':descricao', $produto->getDescricao());
            $stmt->bindValue(':preco', $produto->getPreco());
            $stmt->bindValue(':imagem', $produto->getImagem());
            $stmt->bindValue(':categoria_id', $produto->getCategoriaId());
            $stmt->execute();
        }

        public function atualizar(Produto $produto): void
        {
            $sql = "UPDATE produtos SET nome = :nome, descricao = :descricao, preco = :preco, categoria_id = :categoria_id, imagem = :imagem WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $produto->getNome());
            $stmt->bindValue(':descricao', $produto->getDescricao());
            $stmt->bindValue(':preco', $produto->getPreco());
            $stmt->bindValue(':categoria_id', $produto->getCategoriaId());
            $stmt->bindValue(':imagem', $produto->getImagem());
            $stmt->bindValue(':id', $produto->getId());
            $stmt->execute();
        }

        public function remover(Produto $produto): void 
        {
            $sql = "DELETE FROM produtos WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $produto->getId());
            $stmt->execute();
        }

    }
?>