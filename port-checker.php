<?php
/**
 * Function check if port is open.
 *
 * @var string $ip - Host or IP to check
 * @var string $transport - type of transportation (if empty = TCP). 
 * To use an SSL or TLS client connection over TCP/IP put name of security protocol.
 * @var string $port - number of port to check
 */
function check_if_port_open( $ip, $transport, $port ) {
    if ( 'udp' == strtolower($transport) || 'ssl' == strtolower($transport) || 'tls' == strtolower($transport)  ) {
        $transport = strtolower($transport);
    } else {
        $transport = 'tcp';
    }

    $service_name = getservbyport( $port, $transport );
    $conection = fsockopen( $transport . '://' . $ip, $port, $errno, $errstr, $timeout = 3 );
    if ( $conection ){ 
            socket_set_timeout( $conection, $timeout );
            if ( 'udp' != $transport ){
                $write = fwrite($conection, "\x00");
            }
            if ( ! $write ) {
                echo "Nawiązano połącznie $transport z hostem. Port ($service_name) $port jest zamknięty. Błąd odpowiedzi portu.";
            }
            $startTime = time();
            if ( 'udp' != $transport ){
                $header = fread( $conection, 1 );
            }
            $endTime   = time();
            $timeDiff  = $endTime - $startTime;
            if ( $timeDiff >= $timeout ) {
                echo "Nawiązano połącznie $transport z hostem. Port ($service_name) $port jest otwarty.";
                fclose( $conection );
                return 1;
            }
    } else {
        echo "Port ($service_name) $port jest zamknięty. Wystąpił błąd nr. $errno: $errstr";
        return false;
    }
}
check_if_port_open( '79.133.211.124','', 80 );