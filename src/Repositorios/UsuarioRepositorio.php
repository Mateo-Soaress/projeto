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
            return new Usuario($dados['id'], $dados['nome'], $dados['email'], $dados['cpf'], $dados['perfil'], $dados['senha'], new DateTime($dados['created_at']), new DateTime($dados['updated_at']));
        }

        public function buscarPorId(int $id): ?Usuario {
            $sql = "SELECT id, nome, email, cpf, perfil, senha, created_at, updated_at FROM usuarios WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            $dados = $stmt->fetch();
            return $dados ? $this->formarObjeto($dados): null;
        }

        public function buscarPorCpf(string $cpf): ?Usuario
        {
            $sql = "SELECT id, nome, email, cpf, perfil, senha, created_at, updated_at FROM usuarios WHERE cpf = :cpf";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":cpf", $cpf);
            $stmt->execute();
            $dados = $stmt->fetch();
            return $dados ? $this->formarObjeto($dados): null;
        }

        public function buscarPorEmail(string $email): ?Usuario
        {
            $sql = "SELECT id, nome, email, cpf, perfil, senha, created_at, updated_at FROM usuarios WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            $dados = $stmt->fetch();            
            return $dados ? $this->formarObjeto($dados): null;
        }

        public function buscarPaginado(int $limite, int $offset, ?string $ordem = null, ?string $direcao = 'ASC', ?string $filtroNome = null): array 
        {
            $colunasPermitidas = ['id', 'nome', 'email', 'cpf'];

            $sql = "SELECT * FROM usuarios";

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

            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $listaUsuarios = [];

            foreach ($usuarios as $usuario) {
                $listaUsuarios[] = $this->formarObjeto($usuario);
            }

            return $listaUsuarios;
        }

        public function autenticar(string $email, string $senha): bool
        {
            $usuario = $this->buscarPorEmail($email);
            return $usuario ? password_verify($senha, $usuario->getSenha()):false;
        }

        public function salvar(Usuario $usuario): void
        {
            $sql = "INSERT INTO usuarios (nome, email, cpf, perfil, senha) VALUES (:nome, :email, :cpf, :perfil, :senha)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue("nome", $usuario->getNome());
            $stmt->bindValue("email", $usuario->getEmail());
            $stmt->bindValue("cpf", $usuario->getCpf());
            $stmt->bindValue("perfil", $usuario->getPerfil());
            $stmt->bindValue("senha", password_hash($usuario->getSenha(), PASSWORD_DEFAULT));
            $stmt->execute();
        }

        public function atualizar(Usuario $usuario): void
        {
            $sql = "UPDATE usuarios SET nome = :nome, email = :email, cpf = :cpf, perfil = :perfil, senha = :senha WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue("id", $usuario->getId());
            $stmt->bindValue("nome", $usuario->getNome());
            $stmt->bindValue("email", $usuario->getEmail());
            $stmt->bindValue("cpf", $usuario->getCpf());
            $stmt->bindValue("perfil", $usuario->getPerfil());
            $stmt->bindValue("senha", password_hash($usuario->getSenha(), PASSWORD_DEFAULT));
            $stmt->execute();
        }

        public function listar(): array {
            $sql = "SELECT  id, nome, email, cpf, perfil, senha, created_at, updated_at FROM usuarios ORDER BY id";
            $rs = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return array_map(fn($r) => $this->formarObjeto($r), $rs);
        }

        public function remover(Usuario $usuario): void
        {
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue("id", $usuario->getId());
            $stmt->execute();
        }

        public function contarTotal(): int {
            $sql = "SELECT COUNT(*) AS total FROM usuarios";
            $stmt = $this->pdo->query($sql);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $resultado['total'];
        }

        public function contarTotalFiltrado(string $nome): int {
            $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE nome LIKE CONCAT('%', :nome, '%')";
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