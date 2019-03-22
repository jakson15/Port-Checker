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
    if ( 'udp' == strtolower($transport) || 'ssl' == strtolower($transport) || 'tsl' == strtolower($transport) ) {
        $transport = strtolower($transport) . '://';
    } else {
        $transport = '';
    }

    $conection = fsockopen( $transport . $ip, $port, $errno, $errstr, 30 );
    if ( $conection ){ 
        echo "Port $port jest otwarty.";
        fclose($conection);
        return true;
    } else {
        echo "Port $port jest zamknięty. Wystąpił błąd nr. $errno: $errstr";
        return false;
    }
}
check_if_port_open( '79.133.211.124','udp', 5060 );