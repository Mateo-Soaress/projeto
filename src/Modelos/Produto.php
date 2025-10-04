<?php
    class Produto {
        private int $id;
        private string $nome;
        private string $descricao;
        private float $preco;
        private int $categoria_id;

        public function __construct(int $id, string $nome, string $descricao, float $preco, int $categoria_id) {
            $this->id = $id;
            $this->nome = $nome;
            $this->descricao = $descricao;
            $this->preco = $preco;
            $this->categoria_id = $categoria_id;
        }

        public function getId(): int {
            return $this->id;
        }

        public function getNome(): string {
            return $this->nome;
        }

        public function getDescricao(): string {
            return $this->descricao;
        }

        public function getPreco(): float {
            return $this->preco;
        }

        public function getCategoriaId(): int {
            return $this->categoria_id;
        }
    }
?>