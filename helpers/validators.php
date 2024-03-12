<?php
function isDniAlreadyExists($dni) {
    try {
        $db = (new DB())->connect();
        $sql = "SELECT COUNT(*) FROM customers WHERE dni = :dni AND estado = 1";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':dni', $dni);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count > 0;
    } catch (PDOException $e) {
        return false;
    }
}

function validateDniPeru($dni) {
    $dni = strtoupper(trim($dni));
    // Verificar que el DNI tenga exactamente 8 dígitos y solo números
    if (preg_match('/^\d{8}$/', $dni)) {
        return true;
    }

    return false;
}

function validatDatOfBirth($fecha) {
    // Verificar que la fecha tenga el formato correcto (YYYY-MM-DD o YYYY/MM/DD)
    if (preg_match('/^\d{4}[-\/]\d{2}[-\/]\d{2}$/', $fecha)) {
        list($year, $month, $day) = preg_split('/[-\/]/', $fecha);

        if (checkdate($month, $day, $year)) {
            return true;
        }
    }

    return false;
}

?>