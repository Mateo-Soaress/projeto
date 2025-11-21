<?php
    class Usuario
    {
        private int $id;
        private string $nome;
        private string $email;
        private string $cpf;
        private string $perfil;
        private string $senha;
        private DateTime $created_at;
        private DateTime $updated_at;

        public function __construct(int $id, string $nome, string $email, string $cpf, string $perfil, string $senha, DateTime $created_at, DateTime $updated_at)
        {
            $this->id = $id;
            $this->nome = $nome;
            $this->email = $email;
            $this->cpf = $cpf;
            $this->perfil = $perfil;
            $this->senha = $senha;
            $this->created_at = $created_at;
            $this->updated_at = $updated_at;
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

        public function getCreatedAt(): DateTime
        {
            return $this->created_at;
        }

        public function getUpdatedAt(): DateTime
        {
            return $this->updated_at;
        }
    }
?>
