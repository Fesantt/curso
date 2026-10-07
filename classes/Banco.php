<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| CONEXÃO COM O BANCO (MariaDB)
|--------------------------------------------------------------------------
| PDO é uma classe que JÁ VEM no PHP. Ou seja: para falar com o banco
| a gente usa orientação a objetos também -> new PDO(...)
|
| Antes de usar, rode o arquivo banco.sql:   sudo mariadb < banco.sql
*/
class Banco
{
    // Dados de acesso. São os mesmos que o banco.sql cria.
    const HOST = "localhost";
    const NOME = "mini_marketplace";
    const USUARIO = "aula";
    const SENHA = "aula123";

    // Método estático: chamamos Banco::conectar() sem precisar de "new Banco()".
    // ": PDO" -> o tipo de retorno é um objeto da classe PDO.
    public static function conectar(): PDO
    {
        try {
            $pdo = new PDO(
                "mysql:host=" . self::HOST . ";dbname=" . self::NOME . ";charset=utf8mb4",
                self::USUARIO,
                self::SENHA
            );
        } catch (PDOException $erro) {
            // Se não conseguiu conectar, para tudo e explica o que fazer.
            die("Não consegui conectar no banco. Você rodou o banco.sql? (sudo mariadb &lt; banco.sql)<br>Erro: " . $erro->getMessage());
        }

        // Cada linha do banco vem como array:  $linha["nome"], $linha["preco"]...
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }
}
