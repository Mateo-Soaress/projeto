<?php
    class Usuario
    {
        private int $id;
        private string $nome;
        private string $email;
        private string $cpf;
        private string $perfil;
        private string $senha;

        public function __construct(int $id, string $nome, string $email, string $cpf, string $perfil, string $senha)
        {
            $this->id = $id;
            $this->nome = $nome;
            $this->email = $email;
            $this->cpf = $cpf;
            $this->perfil = $perfil;
            $this->senha = $senha;
        }

        public function getId(): int
        {
            return $this->id;
        }

        public function getNome(): string
        {
            return $this->nome;
        }

        public function getEmail(): string
        {
            return $this->email;
        }

        public function getCpf(): string
        {
            return $this->cpf;
        }

        public function getPerfil(): string {
            return $this->perfil;
        }

        public function getSenha(): string
        {
            return $this->senha;
        }
    }
?>
