# Mini Marketplace — aula de POO com PHP

Projeto bem simples para ensinar orientação a objetos: uma vitrine de produtos, um carrinho e uma página de exemplos. Sem framework, sem arquitetura, só o básico.

## Como rodar

Precisa de PHP 8+ e MariaDB.

```bash
# 1. Criar o banco (só na primeira vez, ou quando quiser zerar os dados)
sudo mariadb < banco.sql

# 2. Subir o site
php -S localhost:8000
```

Abra http://localhost:8000

Para mexer no banco durante a aula:

```bash
mariadb -u aula -paula123 mini_marketplace
```

## Mapa dos arquivos

| Arquivo | O que tem |
|---|---|
| `banco.sql` | Tabelas `vendedores` e `produtos` + dados |
| `classes/Banco.php` | Conexão com o banco (PDO) |
| `classes/Vendedor.php` | A classe mais simples do projeto |
| `classes/Produto.php` | A classe principal: tipos, visibilidade, métodos |
| `classes/ProdutoDigital.php` | Herança |
| `classes/Carrinho.php` | Um objeto que guarda outros objetos |
| `inicio.php` | Busca no banco e cria os objetos |
| `index.php` | Vitrine |
| `carrinho.php` | Carrinho |
| `exemplos.php` | Tabelas "código => resultado" de tudo que a aula cobre |

---

# Fluxo da aula

Dá umas 2 horas no total. Se precisar dividir em duas aulas, corte depois do bloco 4.

A ideia geral é sempre a mesma em cada bloco:

1. **Mostra funcionando** no navegador.
2. **Abre o código** e lê os comentários junto com a turma.
3. **Quebra de propósito** e mostra o erro.
4. **Pergunta** antes de dar a resposta.

O passo 3 é o que mais ensina. Os comentários do código já têm a explicação escrita, então não precisa decorar nada: é só ler e conversar em cima.

## Bloco 0 — Mostrar o site pronto (5 min)

Não abra código ainda. Só navegue:

- Adicione o Teclado e o Curso de PHP no carrinho.
- Tente adicionar o Headset (está esgotado).
- Abra o carrinho e mostre o frete.

**Fale:** "Até o fim da aula vocês vão entender cada linha disso."

**Pergunte:** "Que *coisas* existem nesse site?" Deixe a turma responder: produto, vendedor, carrinho. Escreva no quadro. Essas "coisas" são as classes.

## Bloco 1 — Classe e objeto (15 min)

Abra `classes/Vendedor.php`.

- Classe é o **molde**, objeto é o **bolo**. O molde não se come.
- Mostre o construtor: é o que roda na hora do `new`.
- Mostre o `$this`: "eu mesmo".
- Mostre o `->`: "entra no objeto e pega tal coisa".

Depois abra `inicio.php` e mostre a linha do `new Vendedor(...)`: é ali que o molde vira bolo. São 3 vendedores, 3 objetos, 1 classe só.

**Pergunte:** "Se eu mudar o nome de um vendedor, muda o dos outros?" (Não: cada objeto tem seus próprios dados.)

**Ao vivo:** no fim do `inicio.php`, escreva e recarregue a página:

```php
$vendedores[1]->nome = "Loja do Zé";
```

Só os produtos do vendedor 1 mudam. Apague a linha depois.

## Bloco 2 — Tipos (20 min)

Abra `classes/Produto.php` na parte das propriedades.

- Passe por cada tipo: `int`, `float`, `string`, `bool`.
- `?string` = "texto ou nada". Mostre na vitrine os produtos "Sem descrição."
- `Vendedor $vendedor` = uma classe também é um tipo.
- `: float`, `: bool`, `: void` depois do método = o que ele devolve.

Abra a página **Exemplos da aula**, seções 2 e 3.

**Quebra de propósito 1** — no fim do `inicio.php`:

```php
$produtos[1]->nome = 123;
```

Dá `TypeError`. **Fale:** "O PHP me avisou do erro na hora, em vez de deixar passar um produto chamado 123."

**Quebra de propósito 2** — no `inicio.php`, tire o `(float)` desta linha:

```php
$preco = (float) $linha["preco"];
```

Dá `TypeError` de novo: o banco entrega `"250.00"` como texto. É a deixa para explicar conversão de tipo (cast) e o `declare(strict_types=1)` do topo do arquivo. Coloque o `(float)` de volta.

## Bloco 3 — Operadores (20 min)

Fique na página **Exemplos da aula**, seções 4 a 8, com o `exemplos.php` aberto do lado.

Para cada tabela: tampe a coluna do resultado e pergunte "quanto dá?".

O que não pode faltar:

- `==` contra `===`: `10 == "10"` é `true`, `10 === "10"` é `false`. **Regra para a turma: use sempre `===`.**
- `=` atribui, `==` compara. É o erro mais comum de iniciante.
- `&&` precisa dos dois, `||` basta um, `!` inverte.
- `.` junta texto (em PHP não é `+`).
- `+=` e `++` são atalhos.
- Ternário é um if/else de uma linha.
- `??` é "se não tiver, usa esse".

Depois mostre os operadores no código real, para não ficar só em exemplo solto:

- `Produto.php` → `precoFinal()` (aritméticos) e `aplicarDesconto()` (`||`)
- `Carrinho.php` → `adicionar()` (`??` e `!`) e `frete()` (`&&`)

**Ao vivo:** peça para a turma ditar contas novas e adicione linhas `mostrar(...)` no `exemplos.php`.

## Bloco 4 — Visibilidade e encapsulamento (15 min)

Volte no `Produto.php`.

- `public` = todo mundo mexe.
- `private` = só a própria classe.
- `protected` = a classe e as filhas (vai fazer sentido no próximo bloco).

**Pergunte:** "Por que o preço é `private`?"

**Quebra de propósito 1** — no fim do `inicio.php`:

```php
$produtos[1]->preco = -100;
```

Dá erro: não pode acessar propriedade privada. Troque por:

```php
$produtos[1]->setPreco(-100);
```

Agora dá o **nosso** erro: "O preço tem que ser maior que zero."

**Fale:** "O objeto se protege sozinho. Ninguém consegue deixar um produto com preço negativo, não importa onde no sistema tentem." Isso é encapsulamento.

**Quebra de propósito 2** — no terminal do banco:

```sql
UPDATE produtos SET desconto = 90 WHERE id = 1;
```

Recarregue: a regra do `aplicarDesconto()` barra até dado errado vindo do banco. Volte para 10:

```sql
UPDATE produtos SET desconto = 10 WHERE id = 1;
```

## Bloco 5 — Static e constantes (10 min)

Ainda no `Produto.php`.

- `const DESCONTO_MAXIMO` = valor fixo da classe.
- `static $totalCriados` = um contador só, dividido por todos os produtos.
- `formatarDinheiro()` é estático: não precisa de um produto para formatar dinheiro.
- `->` é para **objeto**, `::` é para **classe**.

Mostre a seção 9 da página de exemplos.

**Pergunte:** "Por que dentro de método estático não existe `$this`?" (Porque não tem objeto nenhum ali, só a classe.)

## Bloco 6 — Herança e polimorfismo (20 min)

Abra `classes/ProdutoDigital.php`.

**Pergunte antes:** "O que um curso online tem de diferente de um teclado?" (Não tem estoque, não tem frete, tem tamanho do arquivo.) "E o que tem de igual?" (Nome, preço, desconto, vendedor.)

- `extends` = "é um". ProdutoDigital **é um** Produto.
- O que é igual não se escreve de novo: mostre que `precoFinal()` nem existe no arquivo e funciona.
- `parent::__construct()` = pede para a mãe fazer a parte dela.
- Os três métodos reescritos: `temEstoque()`, `tipo()`, `calcularFrete()`.

Agora o ponto alto. Abra `Carrinho.php` no método `frete()`:

```php
$frete += $item["produto"]->calcularFrete();
```

**Fale:** "O carrinho não tem nenhum `if` perguntando se é físico ou digital. Ele só manda calcular, e cada produto responde do seu jeito." Isso é polimorfismo.

Mostre no navegador: carrinho só com o E-book tem frete grátis; adicione o Mouse e o frete aparece.

Feche com a seção 10 da página de exemplos e com o `instanceof` no `index.php`.

## Bloco 7 — Banco de dados (15 min)

Abra `banco.sql` e `inicio.php` lado a lado.

Desenhe no quadro:

| Banco | PHP |
|---|---|
| tabela | classe |
| linha | objeto |
| coluna | propriedade |

- `Banco::conectar()` devolve um objeto `PDO`: a própria conexão é orientada a objetos.
- O banco devolve arrays; o `new` transforma em objetos.
- A coluna `tipo` decide qual classe nasce.
- `vendedor_id` é um número no banco e vira um objeto `Vendedor` no PHP.

**Ao vivo:** no terminal do banco, insira um produto e recarregue a vitrine:

```sql
INSERT INTO produtos (nome, preco, estoque, tipo, vendedor_id)
VALUES ('Monitor 24"', 800.00, 2, 'fisico', 2);
```

Ele aparece sem mexer em nenhuma linha de PHP.

---

# Exercícios para a turma

Do mais fácil para o mais difícil.

1. **Método novo.** Em `Vendedor`, crie `ehNovato(): bool` que devolve `true` se a nota for menor que 4.5. Mostre "Novo vendedor" na vitrine.
2. **Operadores.** Em `Produto`, crie `ehCaro(): bool` (preço final acima de R$ 200). Use para mostrar uma etiqueta "Premium".
3. **Produto novo.** Insira 2 produtos pelo SQL, um físico e um digital.
4. **Regra nova.** Em `Produto`, crie `economia(): float` que devolve quanto o cliente economiza com o desconto. Mostre na vitrine: "Você economiza R$ 25,00".
5. **Propriedade nova, do banco até a tela.** Adicione `cidade` no vendedor: coluna na tabela, propriedade na classe, leitura no `inicio.php`, exibição no `index.php`.
6. **Herança.** Crie `ProdutoUsado extends Produto` com a propriedade `estado` ("bom", "regular") e `tipo()` devolvendo "Usado". Precisa de um novo valor na coluna `tipo` e um `elseif` no `inicio.php`.
7. **Desafio.** No `Carrinho`, crie `itemMaisCaro(): ?Produto` que devolve o produto mais caro, ou `null` se o carrinho estiver vazio.

---

# Se algo der errado na hora

| Problema | Solução |
|---|---|
| "Não consegui conectar no banco" | Rode `sudo mariadb < banco.sql` |
| Baguncei os dados | Rode o `banco.sql` de novo, ele recria tudo |
| Página em branco ou erro estranho | Olhe o terminal onde o `php -S` está rodando: o erro aparece lá com arquivo e linha |
| Carrinho com coisa velha | Clique em "Esvaziar carrinho" |
| Esqueci uma linha de teste no `inicio.php` | Apague tudo que estiver depois do último `foreach` |

# O que ficou de fora de propósito

Para manter simples, o projeto **não** tem coisas que um sistema de verdade teria. Vale avisar a turma:

- Não usa `htmlspecialchars` ao imprimir os dados na tela.
- Preço em `float` (o certo é guardar centavos em `int`).
- Não tem login, nem finalizar compra, nem baixa de estoque.
- Não usa namespaces, autoload, nem interfaces.
