<?php
function convertirNumeroALetras($num) {
    $unidad = ["", "uno", "dos", "tres", "cuatro", "cinco", "seis", "siete", "ocho", "nueve"];
    $decena = ["", "diez", "veinte", "treinta", "cuarenta", "cincuenta", "sesenta", "setenta", "ochenta", "noventa"];
    $teens = ["once", "doce", "trece", "catorce", "quince", "dieciséis", "diecisiete", "dieciocho", "diecinueve"];
    $centena = ["", "cien", "doscientos", "trescientos", "cuatrocientos", "quinientos", "seiscientos", "setecientos", "ochocientos", "novecientos"];

    if ($num == 0) {
        return "cero";
    }

    if ($num < 10) {
        return $unidad[$num];
    }

    if ($num < 20) {
        if ($num < 11) {
            return $decena[1];
        } else {
            return $teens[$num - 11];
        }
    }

    if ($num < 100) {
        if ($num % 10 == 0) {
            return $decena[$num / 10];
        } else {
            return $decena[floor($num / 10)] . " y " . $unidad[$num % 10];
        }
    }

    if ($num < 1000) {
        if ($num % 100 == 0) {
            return $centena[$num / 100];
        } else {
            return $centena[floor($num / 100)] . " " . convertirNumeroALetras($num % 100);
        }
    }

    if ($num < 1000000) {
        if ($num % 1000 == 0) {
            return convertirNumeroALetras($num / 1000) . " mil";
        } else {
            return convertirNumeroALetras(floor($num / 1000)) . " mil " . convertirNumeroALetras($num % 1000);
        }
    }

    if ($num < 1000000000) {
        if ($num % 1000000 == 0) {
            return convertirNumeroALetras($num / 1000000) . " millón";
        } else {
            return convertirNumeroALetras(floor($num / 1000000)) . " millón " . convertirNumeroALetras($num % 1000000);
        }
    }

    return "Número demasiado grande";
}

function convertirDecimalALetras($num) {
    $decimal = intval(($num - floor($num)) * 100);
    return convertirNumeroALetras($decimal);
}

function convertirNumeroConDecimalesALetras($num) {
    $parteEntera = floor($num);
    $parteDecimal = convertirDecimalALetras($num);
    return convertirNumeroALetras($parteEntera) . " con " . $parteDecimal . " centavos";
}

// Ejemplo de uso
//echo convertirNumeroConDecimalesALetras(1234.56); // Salida: "mil doscientos treinta y cuatro con cincuenta y seis centavos"
?>
