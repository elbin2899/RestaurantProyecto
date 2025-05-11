# 🍽️ Proyecto Carta Restaurante

Este proyecto es una aplicación web para la gestión digital de la carta de un restaurante. Permite al administrador visualizar, editar, eliminar y controlar el estado de cada plato (activo/inactivo) desde una interfaz visual amigable.

## 📌 Funcionalidades principales

- Listado completo de platos con:
  - Nombre
  - Imagen representativa
  - Categoría
  - Estado (activo/inactivo)
- Acciones por plato:
  - Editar
  - Eliminar
  - Activar/Desactivar
- Gestión de categorías y platos desde el backend
- Vista organizada usando Bootstrap y estilos personalizados
- Imágenes cargadas desde la carpeta `/img/` para una carta más visual

## 🛠️ Estructura del sistema

- `carta_restaurante.php`: vista principal de la carta
- `editar_plato.php`: formulario para editar información de un plato
- `eliminar_plato.php`: elimina un plato de la base de datos
- `toggle_plato.php`: cambia el estado activo/inactivo de un plato
- `guardar_edicion_plato.php`, `procesar_plato.php`, `procesar_categoria.php`: scripts que manejan la lógica del backend

## 📷 Imágenes

Las imágenes de los platos están ubicadas en la carpeta `img/` y se cargan dinámicamente en la tabla de la carta.

## 🚀 Requisitos

- PHP 7+
- Servidor local (XAMPP, MAMP o similar)
- MySQL
- Bootstrap (ya incluido en el proyecto)

## 📦 Instalación

1. Clona este repositorio:
