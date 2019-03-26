<?php
/**
 * Function check if port is open.
 *
 * @var string $ip - Host or IP to check
 * @var string $transport - type of transportation (if empty = TCP). 
 * To use an SSL or TLS client connection over TCP/IP put name of security protocol.
 * @var string $port - number of port to check
 * @var int $timeout - time out for connection (defoult 1s)
 */
function check_if_port_open( $ip, $transport, $port, $timeout = 1.0 ) {
    if ( 'udp' == strtolower($transport) || 'ssl' == strtolower($transport) || 'tls' == strtolower($transport) ) {
        $transport = strtolower($transport);
        $host      = $transport . '://' . $ip;
    } else {
        $transport = 'tcp';
        $host      = $ip;
    }

    $service_name = getservbyport( $port, $transport );
    $connection   = fsockopen( $host, $port, $errno, $errstr, $timeout );
    if ( is_resource( $connection ) ){
        if ( 'udp' == $transport ){
            socket_set_timeout( $connection, $timeout );
            $write = fwrite($connection, "\x00");
            if ( ! $write ) {
                echo "Nawiązano połącznie $transport z hostem. Port ($service_name) $port jest zamknięty. Błąd odpowiedzi portu.";
            }
            $startTime = time();
            $header    = fread( $connection, 1 );
            $endTime   = time();
            $timeDiff  = $endTime - $startTime;
            if ( $timeDiff >= $timeout ) {
                echo "Nawiązano połącznie $transport z hostem. Port ($service_name) $port jest otwarty.";
                fclose( $connection );
                return true;
            }
        } else {
            $write = fwrite($connection, "\x00");
            if ( ! $write ) {
                echo "Nawiązano połącznie $transport z hostem. Port ($service_name) $port jest zamknięty. Błąd odpowiedzi portu.";
                return false;
            }
            echo "Nawiązano połącznie $transport z hostem. Port ($service_name) $port jest otwarty.";
            fclose( $connection );
            return 1;
        }

    } else {
        echo "Port ($service_name) $port jest zamknięty. Wystąpił błąd nr. $errno: $errstr";
        return false;
    }
}
check_if_port_open( '79.133.211.124','udp', '5060' );