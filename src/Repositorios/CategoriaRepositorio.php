<?php
    class CategoriaRepositorio {
        private $pdo;

        public function __construct($pdo) 
        {
            $this->pdo = $pdo;
        }

        public function formarObjeto(array $dados): Categoria 
        {
            return new Categoria($dados['id'], $dados['nome']);
        }

        public function listarObjetos(array $dados): array 
        {
            foreach ($dados as $dado) {
                $categorias[] = $this->formarObjeto($dado);
            }

            return $categorias ?? [];
        }

        public function salvar(Categoria $categoria): void 
        {
            $sql = "INSERT INTO categorias (nome) VALUES (:nome)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $categoria->getNome());
            $stmt->execute();
        }

        public function listar(): array 
        {
            $sql = "SELECT id, nome FROM categorias";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $dados = $stmt->fetchAll();
            $categorias = $dados ? $this->listarObjetos($dados): [];
            return $categorias ?? [];
        }

        public function buscarPorNome(string $nome): ?Categoria 
        {
            $sql = "SELECT id, nome FROM categorias WHERE nome = :nome";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":nome", $nome);
            $stmt->execute();
            $dados = $stmt->fetch();

            return $dados ? $this->formarObjeto($dados): null;
        }

        public function buscarPorId(int $id): ?Categoria 
        {
            $sql = "SELECT id, nome FROM categorias WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            $dados = $stmt->fetch();

            return $dados ? $this->formarObjeto($dados): null;
        }

        public function atualizar(Categoria $categoria): void 
        {
            $sql = "UPDATE categorias SET nome = :nome WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue("id", $categoria->getId());
            $stmt->bindValue("nome", $categoria->getNome());
            $stmt->execute();
        }

        public function remover(Categoria $categoria): void 
        {
            $sql = "DELETE FROM categorias WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue("id", $categoria->getId());
            $stmt->execute();
        }
    }
?>