<?php
    class UsuarioRepositorio
    {
        private PDO $pdo;

        public function __construct(PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        public function formarObjeto(array $dados): Usuario
        {
            return new Usuario($dados['id'], $dados['nome'], $dados['email'], $dados['cpf'], $dados['senha']);
        }

        public function buscarPorCpf(string $cpf): ?Usuario
        {
            $sql = "SELECT id, nome, email, cpf, senha FROM usuarios WHERE cpf = :cpf";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":cpf", $cpf);
            $stmt->execute();
            $dados = $stmt->fetch();
            return $dados ? $this->formarObjeto($dados): null;
        }

        public function buscarPorEmail(string $email): ?Usuario
        {
            $sql = "SELECT id, nome, email, cpf, senha FROM usuarios WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            $dados = $stmt->fetch();            
            return $dados ? $this->formarObjeto($dados): null;
        }

        public function autenticar(string $email, string $senha): bool
        {
            $usuario = $this->buscarPorEmail($email);
            return $usuario ? password_verify($senha, $usuario->getSenha()):false;
        }

        public function salvar(Usuario $usuario): void
        {
            $sql = "INSERT INTO usuarios (nome, email, cpf, senha) VALUES (:nome, :email, :cpf, :senha)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue("nome", $usuario->getNome());
            $stmt->bindValue("email", $usuario->getEmail());
            $stmt->bindValue("cpf", $usuario->getCpf());
            $stmt->bindValue("senha", password_hash($usuario->getSenha(), PASSWORD_DEFAULT));
            $stmt->execute();
        }

        public function atualizar(Usuario $usuario): void
        {
            $sql = "UPDATE usuarios SET nome = :nome, email = :email, cpf = :cpf, senha = :senha WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue("id", $usuario->getId());
            $stmt->bindValue("nome", $usuario->getNome());
            $stmt->bindValue("email", $usuario->getEmail());
            $stmt->bindValue("cpf", $usuario->getCpf());
            $stmt->bindValue("senha", password_hash($usuario->getSenha(), PASSWORD_DEFAULT));
            $stmt->execute();
        }

        public function remover(Usuario $usuario): void
        {
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue("id", $usuario->getId());
            $stmt->execute();
        }
    }
?>