<?php
    class Produto {
        private ?int $id;
        private string $nome;
        private string $descricao;
        private float $preco;
        private int $categoria_id;
        private ?string $imagem;

        public function __construct(?int $id, string $nome, string $descricao, float $preco, int $categoria_id, ?string $imagem = null) {
            $this->id = $id;
            $this->nome = $nome;
            $this->descricao = $descricao;
            $this->preco = $preco;
            $this->categoria_id = $categoria_id;
            $this->imagem = $imagem ?? 'loja-logo.png';
        }

        public function getId(): ?int {
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

        public function getImagemDiretorio(): string {
            $uploadsPath = __DIR__ . '/../uploads/';

            if ($this->imagem && file_exists($uploadsPath . $this->imagem)) {
                return 'uploads/' . $this->imagem;
            }

            return 'img/' . ($this->imagem ?? 'loja-logo.png');
        }

        public function getImagem(): string {
            return $this->imagem;
        }

        public function setImagem(string $imagem): void {
            $this->imagem = $imagem;
        }
    }
?>