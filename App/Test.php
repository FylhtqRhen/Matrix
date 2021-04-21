<?php

namespace App;

use App\Connection;
use App\Text;

class Test
{
    private $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function workSSutuliyDog()
    {
        $vars = file('.env');
        foreach ($vars as $var) {
            putenv(trim($var));
        }

        $connect = Connection::get();

        $data = Text::get();

        $keywords = preg_split('/[\s,?!.+]/', $data);

        $brackets = [
            ')' => '(',
            ']' => '[',
            '}' => '{'
        ];
        $closedBrackets = array_keys($brackets);
        $level = 0;
        $words = [];
        $stack = [];

        foreach ($keywords as $keyword) {
            if (in_array($keyword, $brackets)) {
                $stack[] = $keyword;
                $level++;
            } elseif (in_array($keyword, $closedBrackets)) {
                if(end($stack) != $brackets[$keyword]) {
                    throw  new Exception('Не соблюден баланс скобок');
                }
                array_pop($stack);
                $level--;
            } else {
                $words[$level][] = $keyword;
            }
        }

        foreach ($words as $level => $levelWords) {
            if (count($levelWords) < 3) {
                throw  new Exception('Недостаточно слов в уровне вложенности');
            }
            $words[$level] = array_count_values($levelWords);
        }

        foreach ($words as $level => $levelWords) {
            foreach ($levelWords as $word => $count) {
                if ($word != ''){
                    //var_dump("SELECT * FROM `words` WHERE `word` = '{$word}'  AND `level` = '{$level}'");
                    $isExists = $connect->query("SELECT * FROM `words` WHERE `level` = '{$level}' AND `word` = '{$word}'");
                    if ($isExists->fetchColumn() != 0) {
                        //echo "Я обновиль $word" . PHP_EOL;
                        $connect->query("UPDATE `words` SET `count` = `count` + {$count} WHERE `word` = '{$word}' AND `level` = '{$level}'");
                    } else {
                        //echo "Я создаль $word";
                        $connect->query("INSERT INTO `words` (`word`,`level`,`count`) VALUES ('{$word}','{$level}','{$count}')");
                    }
                }
            }
        }

    }
}