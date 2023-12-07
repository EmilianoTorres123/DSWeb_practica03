<?php
session_start();

try {
    $dsn = "pgsql:host=172.17.0.3;port=5432;dbname=ejemplo;";
    $username = "postgres";
    $password = "postgres";

    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['submit'])) {
        
        $query = "INSERT INTO empleado(clave, nombre, direccion, telefeno)
                  VALUES(:clave, :nombre, :direccion, :telefeno)";

        $statement = $pdo->prepare($query);

        $parameters = [
            ':clave' => $_POST['clave'],
            ':nombre' => $_POST['name'],
            ':direccion' => $_POST['direccion'],
            ':telefeno' => $_POST['telefono']
        ];

        $result = $statement->execute($parameters);

        if ($result) {
            echo "Se registró el empleado.";

            $_POST['clave'] = '';
            $_POST['name'] = '';
            $_POST['direccion'] = '';
            $_POST['telefono'] = '';
        } else {
            echo "Error en la consulta.";
        }
    }

    if (isset($_GET['eliminar'])) {
        
        $claveEliminar = $_GET['eliminar'];

        if (is_numeric($claveEliminar)) {
            $query = "DELETE FROM empleado WHERE clave = :clave";
            $statement = $pdo->prepare($query);
            $statement->bindParam(':clave', $claveEliminar, PDO::PARAM_INT);
            $result = $statement->execute();

            if ($result) {
                echo "Se eliminó el empleado con clave: $claveEliminar";
            } else {
                echo "Error al eliminar el empleado.";
            }
        } else {
            echo "Clave de empleado no válida.";
        }
    }

    
    $consulta = "SELECT * FROM empleado";
    $stmt = $pdo->query($consulta);
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    $pdo = null;
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>