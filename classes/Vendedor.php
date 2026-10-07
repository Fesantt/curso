<?php
// declare(strict_types=1) liga o "modo estrito" de tipos NESTE arquivo.
// Sem isso o PHP tenta converter sozinho ("10" vira 10). Com isso, ele dá erro.
// Tem que ser sempre a primeira linha de código do arquivo.
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| CLASSE
|--------------------------------------------------------------------------
| Uma classe é um MOLDE (uma forma de bolo).
| Ela diz quais DADOS (propriedades) e quais AÇÕES (métodos) uma coisa tem.
|
| A classe Vendedor sozinha não é nenhum vendedor de verdade.
| Quando fazemos  new Vendedor(...)  aí sim nasce um OBJETO (o bolo).
|
|   classe  = molde     -> Vendedor
|   objeto  = o bolo    -> $joao = new Vendedor("João", "joao@email.com");
*/
class Vendedor
{
    /*
    | CONSTRUTOR
    | É o método que roda sozinho na hora do "new".
    |
    | Aqui estamos usando o jeito CURTO do PHP 8 (promoção de propriedades):
    | escrever "public string $nome" dentro dos parênteses já cria a
    | propriedade E já guarda o valor nela. (O jeito longo está em Produto.php)
    |
    | TIPAGEM: "string" antes da variável obriga o valor a ser um texto.
    | "float $nota = 5.0" -> número com vírgula, e 5.0 é o valor padrão
    | caso ninguém informe a nota.
    */
    public function __construct(
        public string $nome,
        public string $email,
        public float $nota = 5.0
    ) {
    }

    // ": string" depois dos parênteses é o TIPO DE RETORNO:
    // esse método promete devolver um texto.
    public function apresentar(): string
    {
        // $this = "eu mesmo", o objeto que está executando o método.
        // ->    = operador para acessar algo de dentro de um objeto.
        // .     = operador que JUNTA textos (concatenação).
        return $this->nome . " (nota " . $this->nota . ")";
    }

    // ": bool" -> devolve verdadeiro (true) ou falso (false)
    public function ehBemAvaliado(): bool
    {
        // >= é um operador de COMPARAÇÃO (maior ou igual). O resultado é sempre bool.
        return $this->nota >= 4.5;
    }
}
