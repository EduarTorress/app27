<?php

namespace Core\Clases;

use Exception;
use PDO;
use PDOException;

class conexion
{
    private $tipo_de_base = 'mysql';
    private $host = '';
    private $nombre_de_base = '';
    private $usuario = '';
    private $contrasena = '';
    /**
     * Conexión PDO compartida durante la misma petición PHP.
     *
     * Esto evita que cada instancia de conexion() cree
     * otra conexión MySQL dentro de la misma petición.
     */
    private static ?PDO $pdo = null;
    /**
     * Último PDOStatement utilizado por execute().
     */
    private $pdoStat = null;
    public $lastinsertid;

    public function __construct()
    {
        $config = require $_ENV['DIR_ROOT'] . "/config/app.php";
        $this->nombre_de_base = $config['database']['database'];
        $this->host           = $config['database']['host'];
        $this->usuario        = $config['database']['username'];
        $this->contrasena     = $config['database']['password'];
    }
    /**
     * ==========================================================
     * CONECTAR
     * ==========================================================
     *
     * Si ya existe una conexión PDO durante esta petición,
     * se reutiliza.
     *
     * Si no existe, se crea una nueva.
     */
    public function conectar(): PDO
    {
        try {
            // ==================================================
            // Ya existe una conexión.
            // Reutilizarla.
            // ==================================================
            if (self::$pdo instanceof PDO) {
                return self::$pdo;
            }
            // ==================================================
            // Configuración de conexión
            // ==================================================
            $connection =
                $this->tipo_de_base .
                ":host=" . $this->host .
                ";dbname=" . $this->nombre_de_base .
                ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => true,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // MUY IMPORTANTE
                // No usar conexiones persistentes.
                PDO::ATTR_PERSISTENT         => false,
            ];
            // ==================================================
            // Crear conexión
            // ==================================================
            self::$pdo = new PDO(
                $connection,
                $this->usuario,
                $this->contrasena,
                $options
            );
            return self::$pdo;
        } catch (PDOException $e) {
            throw new Exception(
                'Error de conexión con la base de datos: ' .
                    $e->getMessage(),
                0,
                $e
            );
        }
    }
    /**
     * ==========================================================
     * GET DATA
     * ==========================================================
     */
    public function getData($sql)
    {
        $stmt = $this->conectar()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * ==========================================================
     * GET DATA SINGLE
     * ==========================================================
     */
    public function getDataSingle($sql)
    {
        $stmt = $this->conectar()->query($sql);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }
    /**
     * ==========================================================
     * GET DATA SINGLE PROP
     * ==========================================================
     */
    public function getDataSingleProp($sql, $prop)
    {
        $data = $this->getDataSingle($sql);
        return $data[$prop] ?? null;
    }
    /**
     * ==========================================================
     * NUM ROWS
     * ==========================================================
     */
    public function numRows($sql)
    {
        $stmt = $this->conectar()->query($sql);
        return $stmt->rowCount();
    }
    /**
     * ==========================================================
     * EXECUTE
     * ==========================================================
     *
     * COMPATIBLE CON TU MÉTODO ANTIGUO.
     *
     * Mantiene:
     *
     * - bindParam()
     * - parámetros posicionales ?
     * - $array_valores
     * - $array_tipos
     * - $return_rows
     *
     * Ejemplo:
     *
     * $sql = "SELECT * FROM tabla WHERE id = ? AND estado = ?";
     *
     * $conexion->execute(
     *     $sql,
     *     1,
     *     [$id, $estado],
     *     ['INT', 'STR']
     * );
     */
    public function execute($query = '', $return_rows = 0, $array_valores = array(), $array_tipos = array())
    {
        /**
         * Obtener la conexión compartida.
         */
        $pdo = $this->conectar();
        /**
         * Preparar consulta.
         */
        $this->pdoStat = $pdo->prepare($query);
        /**
         * Mantener exactamente el comportamiento
         * del execute() antiguo.
         */
        foreach ($array_valores as $posicion => &$valor) {
            $tipo_var =
                isset($array_tipos[$posicion]) &&
                'STR' == $array_tipos[$posicion]
                ? PDO::PARAM_STR
                : PDO::PARAM_INT;
            $this->pdoStat->bindParam(
                $posicion + 1,
                $valor,
                $tipo_var
            );
        }
        /**
         * Ejecutar.
         */
        $result = $this->pdoStat->execute();
        /**
         * Si se solicitaron resultados.
         */
        if (0 < $return_rows && $result) {
            return $return_rows == 2
                ? $this->pdoStat->fetch()
                : $this->pdoStat->fetchAll();
        }

        return $result;
    }
    /**
     * ==========================================================
     * EXECUTE INSTRUCTION
     * ==========================================================
     */
    public function executeInstruction($sql)
    {
        $stmt = $this->conectar()->query($sql);

        return $stmt->rowCount() > 0;
    }
    /**
     * ==========================================================
     * LAST INSERT ID
     * ==========================================================
     */
    public function getLastId()
    {
        return $this->conectar()->lastInsertId();
    }
    /**
     * ==========================================================
     * START TRANSACTION
     * ==========================================================
     */
    public function startTransaction()
    {
        try {
            $pdo = $this->conectar();
            if (!$pdo->inTransaction()) {
                $pdo->beginTransaction();
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    /**
     * ==========================================================
     * INSERT TRANSACTION
     * ==========================================================
     */
    public function insertTransaction($sql, $data)
    {
        $pdo = $this->conectar();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        $this->lastinsertid = $pdo->lastInsertId();
        return true;
    }
    /**
     * ==========================================================
     * SUBMIT TRANSACTION
     * ==========================================================
     */
    public function submitTransaction()
    {
        try {
            $pdo = $this->conectar();
            if ($pdo->inTransaction()) {
                $pdo->commit();
            }
            return true;
        } catch (PDOException $e) {
            try {
                $pdo = self::$pdo;
                if ($pdo instanceof PDO && $pdo->inTransaction()) {
                    $pdo->rollBack();
                }
            } catch (PDOException $rollbackError) {
                // No hacer nada.
            }
            return false;
        }
    }
    /**
     * ==========================================================
     * ROLLBACK
     * ==========================================================
     */
    public function rollback()
    {
        try {
            if (self::$pdo instanceof PDO && self::$pdo->inTransaction()) {
                self::$pdo->rollBack();
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    /**
     * ==========================================================
     * VERIFICAR CONEXIÓN
     * ==========================================================
     */
    public function isConnected()
    {
        return self::$pdo instanceof PDO;
    }
    /**
     * ==========================================================
     * VERIFICAR TRANSACCIÓN
     * ==========================================================
     */
    public function inTransaction()
    {
        return (self::$pdo instanceof PDO && self::$pdo->inTransaction());
    }
    /**
     * ==========================================================
     * CLOSE
     * ==========================================================
     *
     * Cierra manualmente la conexión PDO.
     *
     * IMPORTANTE:
     * No debe ejecutarse mientras todavía se estén
     * realizando consultas con esta conexión.
     */
    public function close()
    {
        try {
            if (self::$pdo instanceof PDO) {
                /**
                 * Si quedó una transacción abierta,
                 * hacer rollback antes de cerrar.
                 */
                if (self::$pdo->inTransaction()) {
                    self::$pdo->rollBack();
                }
                /**
                 * Liberar PDO.
                 */
                self::$pdo = null;
            }
            /**
             * Liberar también el statement.
             */
            $this->pdoStat = null;
            return true;
        } catch (PDOException $e) {
            self::$pdo = null;
            $this->pdoStat = null;
            return false;
        }
    }
    /**
     * Alias.
     */
    public function desconectar()
    {
        return $this->close();
    }
    /**
     * ==========================================================
     * DESTRUCTOR
     * ==========================================================
     *
     * Al finalizar la petición PHP, PDO será liberado.
     */
    public function __destruct()
    {
        // PHP libera automáticamente los recursos al finalizar
        // la petición.
    }
}
