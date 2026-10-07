<?php
declare(strict_types=1);

require_once "inicio.php";

$mensagem = "";

// O formulário de "Adicionar" manda os dados por POST para esta mesma página
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Tudo que vem de formulário chega como STRING ("3").
    // (int) é uma CONVERSÃO de tipo (cast): transforma "3" no número 3.
    // Sem isso, o strict_types daria erro, porque adicionar() exige int.
    $id = (int) ($_POST["id"] ?? 0);

    if (isset($produtos[$id]) && $carrinho->adicionar($produtos[$id])) {
        // Guarda na sessão para lembrar na próxima página
        $_SESSION["carrinho"][$id] = ($_SESSION["carrinho"][$id] ?? 0) + 1;
        $mensagem = $produtos[$id]->nome . " foi para o carrinho!";
    } else {
        $mensagem = "Não foi possível adicionar (sem estoque).";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Mini Marketplace</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Mini Marketplace</h1>
        <nav>
            <a href="index.php">Produtos</a>
            <a href="carrinho.php">Carrinho (<?= $carrinho->quantidadeDeItens() ?>)</a>
            <a href="exemplos.php">Exemplos da aula</a>
        </nav>
    </header>

    <?php if ($mensagem !== ""): ?>
        <p class="mensagem"><?= $mensagem ?></p>
    <?php endif; ?>

    <!-- :: acessa coisas ESTÁTICAS da classe, sem precisar de objeto -->
    <p><?= Produto::$totalCriados ?> produtos cadastrados.</p>

    <div class="produtos">
        <?php foreach ($produtos as $produto): ?>
            <div class="produto">
                <span class="etiqueta"><?= $produto->tipo() ?></span>

                <h2><?= $produto->nome ?></h2>

                <!-- ?? : se a descrição for null, mostra o texto da direita -->
                <p><?= $produto->descricao ?? "Sem descrição." ?></p>

                <?php if ($produto->temDesconto()): ?>
                    <p>
                        <s><?= Produto::formatarDinheiro($produto->getPreco()) ?></s>
                        <span class="desconto">-<?= $produto->getDesconto() ?>%</span>
                    </p>
                <?php endif; ?>

                <p class="preco"><?= Produto::formatarDinheiro($produto->precoFinal()) ?></p>

                <!-- instanceof: pergunta "esse objeto é dessa classe?" -->
                <?php if ($produto instanceof ProdutoDigital): ?>
                    <p>Download: <?= $produto->tamanhoFormatado() ?></p>
                <?php else: ?>
                    <p>Estoque: <?= $produto->getEstoque() ?></p>
                <?php endif; ?>

                <p>
                    Vendido por <?= $produto->vendedor->apresentar() ?>
                    <?= $produto->vendedor->ehBemAvaliado() ? "⭐" : "" ?>
                </p>

                <?php if ($produto->temEstoque()): ?>
                    <form method="post">
                        <input type="hidden" name="id" value="<?= $produto->id ?>">
                        <button>Adicionar ao carrinho</button>
                    </form>
                <?php else: ?>
                    <button disabled>Esgotado</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
