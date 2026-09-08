<?php

namespace Core\Http;

class Session
{

    public function __construct()
    {
         if (session_status() == PHP_SESSION_NONE) {
             session_start();
        }
        //session_start();
        $messages_flash = $_SESSION["messages_flash"] ?? [];
        foreach ($messages_flash as $posicion => $valor) {
            $messages_flash[$posicion]["delete"] = true;
        }
        $_SESSION["messages_flash"] = $messages_flash;
    }

    public function __destruct()
    {
        $messages_flash = $_SESSION["messages_flash"] ?? [];
        foreach ($messages_flash as $posicion => $value) {
            if ($value["delete"] === true) {
                unset($messages_flash[$posicion]);
            }
        }
        $_SESSION["messages_flash"] = $messages_flash;
    }

    /**
     * Añadir un elemento a la sesión
     *
     * @return void
     */
    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Obtener un elemento de la sesión
     *
     * @param string $key Este parametro es la posicion dentro del array
     * @param [type] $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ??  $default;
    }

    /**
     * Remover un elemento de la sesión
     *
     * @return void
     */
    public function remove(string $key)
    {
        unset($_SESSION[$key]);
    }


    public function setFlash(string $key, $value)
    {
        $_SESSION["messages_flash"][$key] = [
            "value" => $value,
            "delete" => false
        ];
    }

    public function getFlash(string $key, $default = null)
    {
        return $_SESSION["messages_flash"][$key]["value"] ?? $default;
    }
    public function cerrarsesion()
    {
        session_destroy();
    }
}
