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
<!DOCTYPE html>
<html>
<head>
    <title>Equipo 4</title>
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" onsubmit="return validarFormulario();">
        <label for="clave">Clave:</label>
        <input type="number" name="clave" id="clave" required value="<?php echo isset($_POST['clave']) ? htmlspecialchars($_POST['clave']) : ''; ?>"><br>

        <label for="name">Nombre:</label>
        <input type="text" name="name" id="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"><br>

        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" id="direccion" required value="<?php echo isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : ''; ?>"><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" id="telefono" required value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>"><br>

        <input type="submit" name="submit" value="Enviar Formulario"><br>
    </form>

    <?php
    if (!empty($registros)) {
        echo "Los registros son:<br>";
        echo "<table border='1'>";
        echo "<tr><th>Clave</th><th>Nombre</th><th>Dirección</th><th>Teléfono</th><th>Acciones</th></tr>";
        foreach ($registros as $index => $registro) {
            echo "<tr>";
            echo "<td><a href=\"javascript:void(0);\" onclick=\"consultarRegistro('{$registro['clave']}');\">{$registro['clave']}</a></td>";
            echo "<td>{$registro['nombre']}</td>";
            echo "<td>{$registro['direccion']}</td>";
            echo "<td>{$registro['telefeno']}</td>";
            echo "<td><a href=\"javascript:void(0);\" onclick=\"confirmarEliminar('{$registro['clave']}');\">Eliminar</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No hay registros.";
    }
    ?>

    <script>
    function consultarRegistro(clave) {
        
        <?php
        if (!empty($registros)) {
            echo "var registros = " . json_encode($registros) . ";\n";
            echo "for (var i = 0; i < registros.length; i++) {\n";
            echo "if (registros[i].clave == clave) {\n";
            echo "document.getElementsByName('clave')[0].value = registros[i].clave;\n";
            echo "document.getElementsByName('name')[0].value = registros[i].nombre;\n";
            echo "document.getElementsByName('direccion')[0].value = registros[i].direccion;\n";
            echo "document.getElementsByName('telefono')[0].value = registros[i].telefeno;\n";
            echo "}\n";
            echo "}\n";
        }
        ?>

    }
    function confirmarEliminar(clave) {
        var confirmacion = confirm("¿quieres eliminar este empleado?");

        if (confirmacion) {
            window.location.href = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?eliminar=" + clave;
        } else {
            
        }
    }
    function validarFormulario() {
        var clave = document.getElementById("clave").value;
        var nombre = document.getElementById("name").value;
        var direccion = document.getElementById("direccion").value;
        var telefono = document.getElementById("telefono").value;

        if (clave === "" || nombre === "" || direccion === "" || telefono === "") {
            alert("Completa todos los campos.");
            return false; 
        }
        return true; 
    }
    </script>
</body>
</html>