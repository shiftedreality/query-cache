<?php

class Tester
{
    private const LIMIT = 100000;

    public function test(string $host, string $port)
    {
        $connection = new PDO(sprintf(
            'mysql:dbname=magento2;host=%s;port=%s',
            $host,
            $port
        ), 'magento2', 'magento2');

        $tableName = 'table_' . time();

        $sql = sprintf(
            'CREATE TABLE %s(id INT( 11 ) PRIMARY KEY, test VARCHAR( 50 ) NOT NULL)',
            $tableName
        );
        $connection->exec($sql);

        $sql = sprintf('INSERT into %s (id, test) VALUES (?, ?)', $tableName);
        $connection->prepare($sql)->execute([1, 'test']);

        $start = microtime(true);

        for ($i = 0; $i < self::LIMIT; $i++) {
            $stmt = $connection->query(sprintf(
                'SELECT * FROM %s ORDER BY id DESC LIMIT 1',
                $tableName
            ));
            $stmt->fetch();
        }

        $time =  microtime(true) - $start;

        $connection = null;

        return $time;
    }

    public function write(string $message)
    {
        echo $message . PHP_EOL;
    }
}

$tester = new Tester();
$tester->write('MariaDB 10.3');
$tester->write($tester->test('mariadb103', '3306'));
$tester->write('MariaDB 10.4');
$tester->write($tester->test('mariadb104', '3306'));
$tester->write('MariaDB 10.4 cahe disabled');
$tester->write($tester->test('mariadb104nc', '3306'));
$tester->write('MySQL 5.6');
$tester->write($tester->test('mysql5', '3306'));
$tester->write('MySQL 8.0');
$tester->write($tester->test('mysql8', '3306'));

$tester->write('End');
