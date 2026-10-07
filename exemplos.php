<?php
declare(strict_types=1);

require_once "inicio.php";

// Função simples só para mostrar na tela: o código de um lado, o resultado do outro.
// "mixed" é o tipo que aceita QUALQUER coisa.
function mostrar(string $codigo, mixed $resultado): void
{
    // var_export mostra o valor do jeito que o PHP enxerga (true, 'texto', 10.5...)
    echo "<tr><td><code>" . htmlspecialchars($codigo) . "</code></td>";
    echo "<td><code>" . htmlspecialchars(var_export($resultado, true)) . "</code></td></tr>";
}

$teclado = $produtos[1]; // Produto, R$ 250 com 10% de desconto
$curso = $produtos[5];   // ProdutoDigital
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Exemplos - Mini Marketplace</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Exemplos da aula</h1>
        <nav>
            <a href="index.php">Produtos</a>
            <a href="carrinho.php">Carrinho (<?= $carrinho->quantidadeDeItens() ?>)</a>
            <a href="exemplos.php">Exemplos da aula</a>
        </nav>
    </header>

    <p>Abra o arquivo <b>exemplos.php</b> e compare o código com o que aparece aqui.</p>

    <h2>1. Classe e objeto</h2>
    <table>
        <tr><th>Código</th><th>Resultado</th></tr>
        <?php
        mostrar('$teclado->nome', $teclado->nome);
        mostrar('$teclado->getPreco()', $teclado->getPreco());
        mostrar('$teclado->precoFinal()', $teclado->precoFinal());
        mostrar('$teclado->vendedor->nome', $teclado->vendedor->nome);
        mostrar('$teclado::class', $teclado::class);
        mostrar('$curso::class', $curso::class);
        ?>
    </table>

    <h2>2. Tipos</h2>
    <table>
        <tr><th>Código</th><th>Resultado</th></tr>
        <?php
        // gettype() diz qual é o tipo de um valor
        mostrar('gettype($teclado->id)', gettype($teclado->id));
        mostrar('gettype($teclado->nome)', gettype($teclado->nome));
        mostrar('gettype($teclado->getPreco())', gettype($teclado->getPreco()));
        mostrar('gettype($teclado->temEstoque())', gettype($teclado->temEstoque()));
        mostrar('gettype($produtos)', gettype($produtos));
        mostrar('gettype($teclado)', gettype($teclado));
        mostrar('$produtos[2]->descricao  (?string)', $produtos[2]->descricao);
        mostrar('(int) "3"   <- conversão (cast)', (int) "3");
        mostrar('(string) 250.5', (string) 250.5);
        ?>
    </table>

    <h2>3. A tipagem protegendo a gente</h2>
    <table>
        <tr><th>Código</th><th>Resultado</th></tr>
        <?php
        // try/catch: "tenta fazer; se der erro, cai no catch em vez de quebrar a página"

        // Tipo errado: aplicarDesconto exige int e mandamos um texto
        try {
            $teclado->aplicarDesconto("dez");
        } catch (TypeError $erro) {
            mostrar('$teclado->aplicarDesconto("dez")', "TypeError: tipo errado, esperava int");
        }

        // Tipo certo, mas valor inválido: a NOSSA regra barra (encapsulamento)
        try {
            $teclado->aplicarDesconto(90);
        } catch (Exception $erro) {
            mostrar('$teclado->aplicarDesconto(90)', $erro->getMessage());
        }

        try {
            $teclado->setPreco(-5.0);
        } catch (Exception $erro) {
            mostrar('$teclado->setPreco(-5.0)', $erro->getMessage());
        }

        // Propriedade private: não dá para acessar de fora da classe
        try {
            $teclado->preco = 1.0;
        } catch (Error $erro) {
            mostrar('$teclado->preco = 1.0', "Error: preco é private");
        }
        ?>
    </table>

    <h2>4. Operadores aritméticos</h2>
    <table>
        <tr><th>Código</th><th>Resultado</th></tr>
        <?php
        mostrar('250 + 120', 250 + 120);
        mostrar('250 - 120', 250 - 120);
        mostrar('120 * 2', 120 * 2);
        mostrar('250 / 4', 250 / 4);
        mostrar('10 % 3   <- resto da divisão', 10 % 3);
        mostrar('2 ** 3   <- potência', 2 ** 3);
        ?>
    </table>

    <h2>5. Operadores de atribuição</h2>
    <table>
        <tr><th>Código</th><th>Resultado</th></tr>
        <?php
        $total = 100;
        mostrar('$total = 100', $total);
        $total += 50;
        mostrar('$total += 50', $total);
        $total -= 30;
        mostrar('$total -= 30', $total);
        $total *= 2;
        mostrar('$total *= 2', $total);
        $total++;
        mostrar('$total++', $total);
        $texto = "Olá";
        $texto .= " turma";
        mostrar('$texto = "Olá"; $texto .= " turma"', $texto);
        ?>
    </table>

    <h2>6. Operadores de comparação</h2>
    <table>
        <tr><th>Código</th><th>Resultado</th></tr>
        <?php
        mostrar('10 == "10"    <- só o valor', 10 == "10");
        mostrar('10 === "10"   <- valor E tipo', 10 === "10");
        mostrar('10 != 5', 10 != 5);
        mostrar('10 !== "10"', 10 !== "10");
        mostrar('250 > 120', 250 > 120);
        mostrar('250 <= 120', 250 <= 120);
        mostrar('1 <=> 2   <- "nave espacial": -1, 0 ou 1', 1 <=> 2);
        ?>
    </table>

    <h2>7. Operadores lógicos</h2>
    <table>
        <tr><th>Código</th><th>Resultado</th></tr>
        <?php
        $temEstoque = $teclado->temEstoque();
        $temDesconto = $teclado->temDesconto();
        $headsetTemEstoque = $produtos[3]->temEstoque();

        mostrar('$temEstoque && $temDesconto   (E)', $temEstoque && $temDesconto);
        mostrar('$headsetTemEstoque && $temDesconto     (E)', $headsetTemEstoque && $temDesconto);
        mostrar('$headsetTemEstoque || $temDesconto     (OU)', $headsetTemEstoque || $temDesconto);
        mostrar('!$temEstoque                  (NÃO)', !$temEstoque);
        ?>
    </table>

    <h2>8. Ternário, ?? e instanceof</h2>
    <table>
        <tr><th>Código</th><th>Resultado</th></tr>
        <?php
        mostrar('$teclado->temEstoque() ? "Disponível" : "Esgotado"', $teclado->temEstoque() ? "Disponível" : "Esgotado");
        mostrar('$produtos[3]->temEstoque() ? "Disponível" : "Esgotado"', $produtos[3]->temEstoque() ? "Disponível" : "Esgotado");
        mostrar('$produtos[2]->descricao ?? "Sem descrição"', $produtos[2]->descricao ?? "Sem descrição");
        mostrar('$teclado instanceof Produto', $teclado instanceof Produto);
        mostrar('$teclado instanceof ProdutoDigital', $teclado instanceof ProdutoDigital);
        mostrar('$curso instanceof ProdutoDigital', $curso instanceof ProdutoDigital);
        mostrar('$curso instanceof Produto   <- herança!', $curso instanceof Produto);
        ?>
    </table>

    <h2>9. Static e constantes (operador ::)</h2>
    <table>
        <tr><th>Código</th><th>Resultado</th></tr>
        <?php
        mostrar('Produto::$totalCriados', Produto::$totalCriados);
        mostrar('Produto::DESCONTO_MAXIMO', Produto::DESCONTO_MAXIMO);
        mostrar('Produto::formatarDinheiro(1234.5)', Produto::formatarDinheiro(1234.5));
        ?>
    </table>

    <h2>10. Herança e polimorfismo</h2>
    <table>
        <tr><th>Código</th><th>Resultado</th></tr>
        <?php
        // Mesmo método, respostas diferentes dependendo da classe do objeto
        mostrar('$teclado->tipo()', $teclado->tipo());
        mostrar('$curso->tipo()', $curso->tipo());
        mostrar('$teclado->calcularFrete()', $teclado->calcularFrete());
        mostrar('$curso->calcularFrete()', $curso->calcularFrete());
        mostrar('$curso->precoFinal()   <- herdado do Produto', $curso->precoFinal());
        mostrar('$curso->tamanhoFormatado()   <- só o digital tem', $curso->tamanhoFormatado());
        ?>
    </table>
</body>
</html>
