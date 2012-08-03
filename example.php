<?php
    require_once('post_iterator.func.php');
    require_once('sql.class.php');

    sql::connect(
        'localhost',
        'root',
        '',
        'blog'
    );

    // удаляем завершающие <br /> из постов

    function br_cleaner_callback($rec, $callback_params)
    {
        if (preg_match('|(<br\s/>\s*)+$|six', $rec['text'], $regs))
        {
            $text = preg_replace('|(<br\s/>\s*)+$|six', '',$rec['text']);
            $sql = 'UPDATE `posts` SET `text`='.sql::esc($text).' WHERE `id`='.intval($rec['id']);
            sql::query($sql);
            echo ' - post '.$rec['id'].' fixed<br />';
        }
    }

    post_iterator(  'posts',                    // таблица
                    'br_cleaner_callback',      // метод который будет вызван для каждой записи
                    array(),                    // массив ключ=значение, передаваемый в callback как $callback_params
                    array('echo'=>TRUE)         // параметры запросов, описание см. в объявлении функции
    );
