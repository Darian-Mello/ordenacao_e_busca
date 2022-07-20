<?php 
    function countingSort ($tripulantes) {
        $maior = $tripulantes[0]['log'];
        for ($i = 0; $i < count($tripulantes); $i++) {
            if($tripulantes[$i]['log'] > $maior) {
                $maior = $tripulantes[$i]['log'];
            }
        }

        $freq = [];
        for ($i = 0; $i < $maior+1; $i++) {
            $freq[$i] = [];
        }

        for ($i = 0; $i < count($tripulantes); $i++) {
            array_push($freq[$tripulantes[$i]['log']], $tripulantes[$i]);
        }

        $resposta = [];
        for ($i = 0; $i < count($freq); $i++) {
            for ($j = 0; $j < count($freq[$i]); $j++) {
                array_push($resposta, $freq[$i][$j]);
            }
        }
        return $resposta;
    }

    function ordenaVetor ($vetor) {
        $tripulanes_por_mes = array(
            0 => [],
            1 => [],
            2 => [],
            3 => [],
            4 => [],
            5 => [],
            6 => [],
            7 => [],
            8 => [],
            9=> [],
            10 => [],
            11 => []
        );

        $cont = 0;
        foreach ($vetor as $v) {
            if ($v != null) {
                $tripulanes_por_mes[$cont] = countingSort($v);
            }
            $cont++;
        }
        return $tripulanes_por_mes;
    }

    function carregaVetor () {
        $tripulantes = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', file_get_contents('logNaveSbornia.txt', true)), true);

        $tripulanes_por_mes = array(
            'January' => [],
            'February' => [],
            'March' => [],
            'April' => [],
            'May' => [],
            'June' => [],
            'July' => [],
            'August' => [],
            'September' => [],
            'October' => [],
            'November' => [],
            'December' => []
        );

        if ($tripulantes[0]) {
            foreach ($tripulantes as $t) {
                $temp = array(
                    'log' => $t['log'],
                    'user' => $t['user'],
                    'month' => $t['month']
                );
                switch (strtolower($t['month'])) {
                    case 'january' :
                        array_push($tripulanes_por_mes['January'], $temp);
                        break;
                    case 'february' :
                        array_push($tripulanes_por_mes['February'], $temp);
                        break;
                    case 'march' :
                        array_push($tripulanes_por_mes['March'], $temp);
                        break;
                    case 'april' :
                        array_push($tripulanes_por_mes['April'], $temp);
                        break;
                    case 'may' :
                        array_push($tripulanes_por_mes['May'], $temp);
                        break;
                    case 'june' :
                        array_push($tripulanes_por_mes['June'], $temp);
                        break;
                    case 'july' :
                        array_push($tripulanes_por_mes['July'], $temp);
                        break;
                    case 'august' :
                        array_push($tripulanes_por_mes['August'], $temp);
                        break;
                    case 'september' :
                        array_push($tripulanes_por_mes['September'], $temp);
                        break;
                    case 'october' :
                        array_push($tripulanes_por_mes['October'], $temp);
                        break;
                    case 'november' :
                        array_push($tripulanes_por_mes['November'], $temp);
                        break;
                    case 'december' :
                        array_push($tripulanes_por_mes['December'], $temp);
                        break;
                }
            }
            $resposta = ordenaVetor($tripulanes_por_mes);
            return $resposta;
        }
    }

    function buscaBinaria ($A, $k, $n) {
        if ($n == 0) {
            return -1;
        }
        $min = 0;
        $max = $n-1;
        while ($min <= $max) {
            $mid = ($max + $min) / 2;
        
            if ($A[$mid]['log'] == $k) {
                return $mid;
            }
            if ($k < $A[$mid]['log']) {
                $max = $mid-1;
            }
            else {
                $min = $mid+1;
            }
        }
        return -1;
    }
    
    function buscaLog ($vetor, $log) {
        $resposta = "";
        $encontrou_log = false;

        $cont = 0;
        foreach ($vetor as $vetor_mes) {
            $cont++;
            $start_busca = microtime(true);
            $posicao = intval(buscaBinaria($vetor_mes, $log, count($vetor_mes)));
            $end_busca = microtime(true);
            $execution_time_busca = ($end_busca - $start_busca);
            $resposta .= "<br>Tempo de busca do mês $cont: " . round($execution_time_busca, 3) . "<br>";
            
            if ($posicao == -1) {
                continue;
            }

            $resposta .= "Log encontrado:<br>";
            
            $n = $posicao - 1;
            if ($n >= 0) {
                while ($vetor_mes[$posicao]['log'] == $vetor_mes[$n]['log']) {
                    $resposta .= $n . ' - log: ' . $vetor_mes[$n]['log'] . ' - month: ' . $vetor_mes[$n]['month']. ' - user: ' . $vetor_mes[$n]['user'] . ';<br>';
                    $n--;
                    if ($n < 0) {
                        break;
                    }
                }
            }

            $resposta .= $posicao . ' - log: ' . $vetor_mes[$posicao]['log'] . ' - month: ' . $vetor_mes[$posicao]['month']. ' - user: ' . $vetor_mes[$posicao]['user'] . ';<br>';
            
            $n = $posicao + 1;
            if ($n < count($vetor_mes)) {
                while ($vetor_mes[$posicao]['log'] == $vetor_mes[$n]['log']) {
                    $resposta .= $n . ' - log: ' . $vetor_mes[$n]['log'] . ' - month: ' . $vetor_mes[$n]['month']. ' - user: ' . $vetor_mes[$n]['user'] . ';<br>';
                    $n++;
                    if ($n == count($vetor_mes)) {
                        break;
                    }
                }
            }
        }
        return $resposta;
    }

    function buscaLogMes ($vetor, $log, $mes) {
        $vetor_mes = $vetor[$mes];
        $start_busca = microtime(true);
        $posicao = intval(buscaBinaria($vetor_mes, $log, count($vetor_mes)));
        $end_busca = microtime(true);
        $execution_time_busca = ($end_busca - $start_busca);
        $resposta = "<br>Tempo de busca " . round($execution_time_busca, 3) . "<br>";

        if ($posicao == -1) {
            return "O log não foi encontrado";
        }
        
        $n = $posicao - 1;
        $resposta .= "<br>Log encontrado nas seguintes posições:<br>";
        if ($n >= 0) {
            while ($vetor_mes[$posicao]['log'] == $vetor_mes[$n]['log']) {
                $resposta .= $n . ' - log: ' . $vetor_mes[$n]['log'] . ' - month: ' . $vetor_mes[$n]['month']. ' - user: ' . $vetor_mes[$n]['user'] . ';<br>';
                $n--;
                if ($n < 0) {
                    break;
                }
            }
        }

        $resposta .= $posicao . ' - log: ' . $vetor_mes[$posicao]['log'] . ' - month: ' . $vetor_mes[$posicao]['month']. ' - user: ' . $vetor_mes[$posicao]['user'] . ';<br>';
        
        $n = $posicao + 1;
        if ($n < count($vetor_mes)) {
            while ($vetor_mes[$posicao]['log'] == $vetor_mes[$n]['log']) {
                $resposta .= $n . ' - log: ' . $vetor_mes[$n]['log'] . ' - month: ' . $vetor_mes[$n]['month']. ' - user: ' . $vetor_mes[$n]['user'] . ';<br>';
                $n++;
                if ($n == count($vetor_mes)) {
                    break;
                }
            }
        }
        return $resposta;
    }

    function index () {
        $res = "Houve um erro!";
        try {
            ini_set('memory_limit', '3G'); 
            $log = 0;
            $mes = 0;
            if (isset($_POST['log']) && isset($_POST['log'])) {
                $log = $_POST['log'];
                $mes = $_POST['mes'];
            }

            if ($mes == 0) {
                $start_leitura = microtime(true);
                $tripulanes_por_mes = carregaVetor();
                $end_leitura = microtime(true);
                $execution_time_carregar_arquivo = ($end_leitura - $start_leitura);
                $res = "Tempo para leitura e ordenação do arquivo: " . round($execution_time_carregar_arquivo, 3) . "<br>";
                $res .= buscaLog($tripulanes_por_mes, $log);
            } else {
                $start_leitura = microtime(true);
                $tripulanes_por_mes = carregaVetor();
                $end_leitura = microtime(true);
                $execution_time_carregar_arquivo = ($end_leitura - $start_leitura);
                $res = "Tempo para leitura e ordenação do arquivo: " . round($execution_time_carregar_arquivo, 3) . "<br>";
                $mes--;
                $res .= buscaLogMes($tripulanes_por_mes, $log, $mes);
            }

        } catch (Exception $e) {
            $res = "Houve um erro!";
            echo '<pre>';
            print_r($e);
            echo '</pre>';
        }
        echo $res;
    }
    index();
?>