<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| HERANÇA
|--------------------------------------------------------------------------
| "extends" = ProdutoDigital É UM Produto.
| Ele ganha de graça tudo que o Produto tem (nome, preço, desconto, etc.)
| e só escreve o que é DIFERENTE.
|
|   Produto          -> classe mãe (pai / superclasse)
|   ProdutoDigital   -> classe filha (subclasse)
*/
class ProdutoDigital extends Produto
{
    // Propriedade que só o produto digital tem
    public int $tamanhoMb;

    public function __construct(int $id, string $nome, float $preco, Vendedor $vendedor, int $tamanhoMb)
    {
        // parent:: = "a classe mãe". Aqui chamamos o construtor do Produto
        // para ele fazer o trabalho que já sabe fazer.
        // Produto digital não tem estoque de verdade, então mandamos 0.
        parent::__construct($id, $nome, $preco, 0, $vendedor);

        $this->tamanhoMb = $tamanhoMb;
    }

    /*
    | SOBRESCRITA (override) + POLIMORFISMO
    | Os métodos abaixo têm o MESMO nome dos métodos do Produto,
    | mas se comportam de outro jeito. O carrinho chama calcularFrete()
    | sem precisar saber se o produto é físico ou digital: cada um responde
    | do seu jeito. Isso é polimorfismo.
    */

    // Um arquivo para download nunca acaba
    public function temEstoque(int $quantidade = 1): bool
    {
        return true;
    }

    public function tipo(): string
    {
        return "Digital";
    }

    // Download não tem frete
    public function calcularFrete(): float
    {
        return 0.0;
    }

    public function tamanhoFormatado(): string
    {
        // Operador TERNÁRIO: condição ? valor se verdadeiro : valor se falso
        // É um if/else de uma linha só.
        return $this->tamanhoMb >= 1024
            ? round($this->tamanhoMb / 1024, 1) . " GB"
            : $this->tamanhoMb . " MB";
    }
}
