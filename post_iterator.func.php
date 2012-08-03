<?php

    /**
     *  Итератор для постепенного обхода больших объемов данных
     *  $table = название таблицы
     *  $result_rec_callback = функция которая будет вызвана, для каждой записи из полученного результат
     *                         на вход будет передан массив с записью и $callback_params
     *  $callback_params = массив для передачи значений в callback функцию
     *  $query_params = различные параметры для формирования массива
     */
    function post_iterator($table, $result_rec_callback, $callback_params=array(), $query_params=array())
    {
          if (!function_exists($result_rec_callback)) throw new Exception('Undefined method: '.$result_rec_callback);

          $query_params_default = array(
            'perpage'=>500,     // обрабатывается кол-во постов за 1 запрос
            'where_max_id'=>'', // добавляем условие Поиска при подсчете максимального ID
            'where_iter'=>'',   // добавляем условие для выбора записей
            'id_column'=>'id',  // название primary столбца, обязательно должен быть int
            'echo'=>FALSE,       // выводить ли информацию по обрабатываемым постам
            'time_out'=>300,    // какой тайм аут устанавливать для обработки одного запроса
          );
          foreach ($query_params_default as $var=>$val) if (!isset($query_params[$var])) $query_params[$var] = $val;

          $sql ='';
          $sql .= ' SELECT `'.$query_params['id_column'].'` FROM `'.$table.'`';
          if ($query_params['where_max_id']) $sql .= ' WHERE '.$query_params['where_max_id'];
          $sql .= ' ORDER BY `'.$query_params['id_column'].'` DESC LIMIT 1';

          $pages = ceil(sql::get_var($sql)/$query_params['perpage']);

          for ($i=0; $i<$pages; $i++)
          {
              if ($query_params['time_out']!==FALSE) set_time_limit($query_params['time_out']);
              $from = $i*$query_params['perpage'];
              $to = ($i+1)*$query_params['perpage'];
              if ($query_params['echo']) echo 'Process posts from '.$from.' to '.$to.'<br />';

              $sql = 'SELECT * FROM `'.$table.'` WHERE (`'.$query_params['id_column'].'`>='.$from.' AND `'.$query_params['id_column'].'`<'.$to.')';
              if ($query_params['where_iter']) $sql .=' AND ('.$query_params['where_iter'].')';

              if ($recs = sql::get_results($sql))
              {
                  foreach ($recs as $rec) {
                      $result_rec_callback($rec, $callback_params);
                  }
              }
          }
    }