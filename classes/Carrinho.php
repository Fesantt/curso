<?php
declare(strict_types=1);

class Carrinho
{
    const FRETE_GRATIS_A_PARTIR_DE = 300.0;

    /*
    | "array" também é um tipo. Aqui cada posição do array guarda
    | um produto e a quantidade dele:
    |
    |   [
    |      3 => ["produto" => (objeto Produto), "quantidade" => 2],
    |      5 => ["produto" => (objeto Produto), "quantidade" => 1],
    |   ]
    |
    | A chave (3, 5...) é o id do produto.
    */
    private array $itens = [];

    // O parâmetro é do tipo Produto. Como ProdutoDigital É UM Produto (herança),
    // ele também é aceito aqui.
    public function adicionar(Produto $produto, int $quantidade = 1): bool
    {
        // ?? (null coalescing): "use o valor da esquerda; se não existir, use o da direita"
        // Se o produto ainda não está no carrinho, a quantidade atual é 0.
        $quantidadeAtual = $this->itens[$produto->id]["quantidade"] ?? 0;

        $novaQuantidade = $quantidadeAtual + $quantidade;

        // ! é o operador de NEGAÇÃO: inverte true/false. Lê-se "se NÃO tem estoque".
        if (!$produto->temEstoque($novaQuantidade)) {
            return false;
        }

        $this->itens[$produto->id] = [
            "produto" => $produto,
            "quantidade" => $novaQuantidade,
        ];

        return true;
    }

    public function remover(int $idProduto): void
    {
        unset($this->itens[$idProduto]);
    }

    public function getItens(): array
    {
        return $this->itens;
    }

    public function estaVazio(): bool
    {
        // === compara VALOR e TIPO (igualdade estrita). Prefira sempre ao ==
        return count($this->itens) === 0;
    }

    public function quantidadeDeItens(): int
    {
        $total = 0;

        foreach ($this->itens as $item) {
            // += é atalho para:  $total = $total + $item["quantidade"];
            $total += $item["quantidade"];
        }

        return $total;
    }

    public function subtotal(): float
    {
        $soma = 0.0;

        foreach ($this->itens as $item) {
            $soma += $item["produto"]->precoFinal() * $item["quantidade"];
        }

        return $soma;
    }

    public function frete(): float
    {
        // && é o operador lógico E: as DUAS condições precisam ser verdadeiras.
        if (!$this->estaVazio() && $this->subtotal() >= self::FRETE_GRATIS_A_PARTIR_DE) {
            return 0.0;
        }

        $frete = 0.0;

        foreach ($this->itens as $item) {
            // POLIMORFISMO na prática: produto físico devolve 15.0, digital devolve 0.0
            $frete += $item["produto"]->calcularFrete();
        }

        return $frete;
    }

    public function total(): float
    {
        return $this->subtotal() + $this->frete();
    }
}
