<?php
declare(strict_types=1);

// Esse arquivo é incluído no começo de todas as páginas.
// Ele carrega as classes, busca os dados no banco, cria os objetos e monta o carrinho.

// require_once = "traga o código desse arquivo para cá (só uma vez)".
// A ordem importa: ProdutoDigital precisa que Produto já exista.
require_once "classes/Banco.php";
require_once "classes/Vendedor.php";
require_once "classes/Produto.php";
require_once "classes/ProdutoDigital.php";
require_once "classes/Carrinho.php";

// :: chama o método estático. $pdo é um OBJETO da classe PDO (que já vem no PHP).
$pdo = Banco::conectar();

/*
|--------------------------------------------------------------------------
| DO BANCO PARA OBJETOS
|--------------------------------------------------------------------------
| O banco devolve LINHAS (arrays). Nós transformamos cada linha em um
| OBJETO com "new". Linha da tabela vendedores -> objeto Vendedor.
|
| O banco entrega os números DECIMAL como texto ("250.00"), então usamos
| (float) e (int) para converter. Sem isso o strict_types daria erro.
*/
$vendedores = [];

// query() executa o SQL. O foreach passa por cada linha que voltou.
foreach ($pdo->query("SELECT * FROM vendedores") as $linha) {
    // A chave do array é o id, para ficar fácil de achar depois.
    $vendedores[(int) $linha["id"]] = new Vendedor(
        $linha["nome"],
        $linha["email"],
        (float) $linha["nota"]
    );
}

$produtos = [];

foreach ($pdo->query("SELECT * FROM produtos ORDER BY id") as $linha) {
    $id = (int) $linha["id"];
    $preco = (float) $linha["preco"];

    // vendedor_id é só um número no banco. Aqui trocamos pelo OBJETO Vendedor.
    $vendedor = $vendedores[(int) $linha["vendedor_id"]];

    // A coluna "tipo" decide qual classe usar
    if ($linha["tipo"] === "digital") {
        $produto = new ProdutoDigital($id, $linha["nome"], $preco, $vendedor, (int) $linha["tamanho_mb"]);
    } else {
        $produto = new Produto($id, $linha["nome"], $preco, (int) $linha["estoque"], $vendedor);
    }

    // Usando o objeto: chamando método e mexendo em propriedade pública
    $produto->aplicarDesconto((int) $linha["desconto"]);
    $produto->descricao = $linha["descricao"]; // pode ser null, por isso o tipo é ?string

    $produtos[$id] = $produto;
}

/*
|--------------------------------------------------------------------------
| SESSÃO + CARRINHO
|--------------------------------------------------------------------------
| O PHP "esquece" tudo a cada página carregada. A sessão é a memória
| que sobrevive entre uma página e outra.
| Na sessão guardamos só  [id do produto => quantidade].
| A cada página, remontamos o objeto Carrinho a partir disso.
*/
session_start();

// ??= : "se não existir (ou for null), atribua". Cria o carrinho vazio na 1ª visita.
$_SESSION["carrinho"] ??= [];

$carrinho = new Carrinho();

foreach ($_SESSION["carrinho"] as $id => $quantidade) {
    // isset() verifica se aquela posição existe no array
    if (isset($produtos[$id])) {
        $carrinho->adicionar($produtos[$id], $quantidade);
    }
}
