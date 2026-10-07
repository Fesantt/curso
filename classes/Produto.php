<?php
declare(strict_types=1);

class Produto
{
    /*
    |--------------------------------------------------------------------------
    | CONSTANTE DE CLASSE
    |--------------------------------------------------------------------------
    | Valor fixo, que nunca muda. Pertence à CLASSE, não a um objeto.
    | Acessa com ::  ->  Produto::DESCONTO_MAXIMO  (ou self:: aqui dentro)
    */
    const DESCONTO_MAXIMO = 50;
    const FRETE_FIXO = 15.0;

    /*
    |--------------------------------------------------------------------------
    | PROPRIEDADE ESTÁTICA
    |--------------------------------------------------------------------------
    | "static" = também pertence à classe. Existe UMA só, dividida por todos
    | os produtos. Usamos para contar quantos produtos já foram criados.
    */
    public static int $totalCriados = 0;

    /*
    |--------------------------------------------------------------------------
    | PROPRIEDADES (os dados de cada objeto) + TIPAGEM + VISIBILIDADE
    |--------------------------------------------------------------------------
    | Tipos:
    |   int     -> número inteiro        (1, 20, -3)
    |   float   -> número com casas      (19.90)
    |   string  -> texto                 ("Teclado")
    |   bool    -> true ou false
    |   ?string -> string OU null (o ? quer dizer "pode ser vazio/nulo")
    |   Vendedor-> um objeto de outra classe também é um tipo!
    |
    | Visibilidade (quem pode mexer):
    |   public    -> qualquer um, de qualquer lugar
    |   private   -> só a própria classe
    |   protected -> a própria classe e as classes FILHAS (ver ProdutoDigital)
    */
    public int $id;
    public string $nome;
    public Vendedor $vendedor;
    public ?string $descricao = null;

    // private: ninguém de fora pode fazer $produto->preco = -100;
    // Para mexer no preço tem que passar pelo método setPreco() (ENCAPSULAMENTO).
    private float $preco;
    private int $desconto = 0; // em porcentagem

    // protected: a classe filha ProdutoDigital consegue enxergar
    protected int $estoque;

    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR (jeito longo, passo a passo)
    |--------------------------------------------------------------------------
    */
    public function __construct(int $id, string $nome, float $preco, int $estoque, Vendedor $vendedor)
    {
        // = é o operador de ATRIBUIÇÃO: guarda o valor da direita na esquerda.
        $this->id = $id;
        $this->nome = $nome;
        $this->estoque = $estoque;
        $this->vendedor = $vendedor;

        // Em vez de atribuir direto, usamos o setPreco para já validar.
        $this->setPreco($preco);

        // self:: = "esta classe". ++ soma 1 (operador de incremento).
        self::$totalCriados++;
    }

    /*
    |--------------------------------------------------------------------------
    | GETTER e SETTER (encapsulamento)
    |--------------------------------------------------------------------------
    */
    public function getPreco(): float
    {
        return $this->preco;
    }

    // ": void" = esse método não devolve nada.
    public function setPreco(float $preco): void
    {
        // <= é comparação (menor ou igual)
        if ($preco <= 0) {
            // Lança um erro. Quem chamou pode tratar com try/catch.
            throw new Exception("O preço tem que ser maior que zero.");
        }

        $this->preco = $preco;
    }

    public function getEstoque(): int
    {
        return $this->estoque;
    }

    public function getDesconto(): int
    {
        return $this->desconto;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS (as ações do objeto)
    |--------------------------------------------------------------------------
    */
    public function aplicarDesconto(int $porcento): void
    {
        // || é o operador lógico OU: basta UMA das condições ser verdadeira.
        if ($porcento < 0 || $porcento > self::DESCONTO_MAXIMO) {
            throw new Exception("Desconto tem que ficar entre 0 e " . self::DESCONTO_MAXIMO . "%.");
        }

        $this->desconto = $porcento;
    }

    public function temDesconto(): bool
    {
        return $this->desconto > 0;
    }

    public function precoFinal(): float
    {
        // Operadores ARITMÉTICOS: * (vezes)  / (dividir)  - (menos)  + (mais)
        // Ex: preço 200 com 10% -> 200 * 10 / 100 = 20 de desconto -> 180
        $valorDoDesconto = $this->preco * $this->desconto / 100;

        return $this->preco - $valorDoDesconto;
    }

    // $quantidade = 1 -> parâmetro com valor padrão (opcional)
    public function temEstoque(int $quantidade = 1): bool
    {
        return $this->estoque >= $quantidade;
    }

    public function tipo(): string
    {
        return "Físico";
    }

    public function calcularFrete(): float
    {
        return self::FRETE_FIXO;
    }

    /*
    | MÉTODO ESTÁTICO
    | Não precisa de objeto para ser chamado: Produto::formatarDinheiro(10.5)
    | Por isso aqui dentro NÃO existe $this.
    */
    public static function formatarDinheiro(float $valor): string
    {
        return "R$ " . number_format($valor, 2, ",", ".");
    }
}
