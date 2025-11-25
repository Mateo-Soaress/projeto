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

        public function buscarPaginado(int $limite, int $offset, ?string $ordem = null, ?string $direcao = 'ASC', ?string $filtroNome = null): array 
        {
            $colunasPermitidas = ['id', 'nome', 'preco'];

            $sql = "SELECT * FROM produtos";

            if ($filtroNome) {
                $sql .= " WHERE nome LIKE CONCAT('%', :nome, '%')";
            }

            if ($ordem !== null && in_array(strtolower($ordem), $colunasPermitidas)) {
                $direcao = strtoupper($direcao) == 'DESC' ? 'DESC' : 'ASC';
                $sql .= " ORDER BY {$ordem} {$direcao}";
            }

            $sql .= " LIMIT :limit OFFSET :offset";

            $stmt = $this->pdo->prepare($sql);            
            $stmt->bindValue("limit", $limite, PDO::PARAM_INT);
            $stmt->bindValue("offset", $offset, PDO::PARAM_INT);

            if ($filtroNome) {
                $stmt->bindValue("nome", $filtroNome, PDO::PARAM_STR);
            }

            $stmt->execute();

            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $listaProdutos = [];

            foreach ($produtos as $produto) {
                $listaProdutos[] = $this->formarObjeto($produto);
            }

            return $listaProdutos;
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

         public function listarComFiltro(string $filtroNome): array 
        {
            $sql = "SELECT id, nome, descricao, preco, categoria_id, imagem FROM produtos WHERE nome LIKE CONCAT('%', :nome, '%')";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue("nome", $filtroNome, PDO::PARAM_STR);
            $stmt->execute();
            $produtos = [];
            while ($row = $stmt->fetch()) {
                $produtos[] = $this->formarObjeto($row);
            }
            return $produtos;
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

        public function contarTotal(): int {
            $sql = "SELECT COUNT(*) AS total FROM produtos";
            $stmt = $this->pdo->query($sql);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $resultado['total'];
        }

        public function contarTotalFiltrado(string $nome): int {
            $sql = "SELECT COUNT(*) AS total FROM produtos WHERE nome LIKE CONCAT('%', :nome, '%')";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue("nome", $nome, PDO::PARAM_STR);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$resultado) {
                return 0;
            }
            
            return (int) $resultado['total'];
        }

    }
?>