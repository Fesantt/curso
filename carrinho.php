<?php
declare(strict_types=1);

require_once "inicio.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $acao = $_POST["acao"] ?? "";

    if ($acao === "remover") {
        $id = (int) ($_POST["id"] ?? 0);
        $carrinho->remover($id);
        unset($_SESSION["carrinho"][$id]);
    }

    if ($acao === "esvaziar") {
        $carrinho = new Carrinho(); // um objeto novinho, vazio
        $_SESSION["carrinho"] = [];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho - Mini Marketplace</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Carrinho</h1>
        <nav>
            <a href="index.php">Produtos</a>
            <a href="carrinho.php">Carrinho (<?= $carrinho->quantidadeDeItens() ?>)</a>
            <a href="exemplos.php">Exemplos da aula</a>
        </nav>
    </header>

    <?php if ($carrinho->estaVazio()): ?>
        <p>Seu carrinho está vazio.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Produto</th>
                <th>Tipo</th>
                <th>Preço</th>
                <th>Qtd</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
            <?php foreach ($carrinho->getItens() as $item): ?>
                <?php
                $produto = $item["produto"];
                $quantidade = $item["quantidade"];
                ?>
                <tr>
                    <td><?= $produto->nome ?></td>
                    <td><?= $produto->tipo() ?></td>
                    <td><?= Produto::formatarDinheiro($produto->precoFinal()) ?></td>
                    <td><?= $quantidade ?></td>
                    <td><?= Produto::formatarDinheiro($produto->precoFinal() * $quantidade) ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="acao" value="remover">
                            <input type="hidden" name="id" value="<?= $produto->id ?>">
                            <button>Remover</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p>Subtotal: <?= Produto::formatarDinheiro($carrinho->subtotal()) ?></p>

        <p>
            Frete:
            <?= $carrinho->frete() === 0.0 ? "Grátis" : Produto::formatarDinheiro($carrinho->frete()) ?>
            <small>(grátis a partir de <?= Produto::formatarDinheiro(Carrinho::FRETE_GRATIS_A_PARTIR_DE) ?>)</small>
        </p>

        <p class="preco">Total: <?= Produto::formatarDinheiro($carrinho->total()) ?></p>

        <form method="post">
            <input type="hidden" name="acao" value="esvaziar">
            <button>Esvaziar carrinho</button>
        </form>
    <?php endif; ?>
</body>
</html>
