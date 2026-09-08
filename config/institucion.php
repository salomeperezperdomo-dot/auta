<?php

// Configuración de la institución educativa que usa el sistema.
//
// Este archivo existe para que AUTA sea replicable a otro colegio sin
// tocar código: basta con cambiar estos dos valores (o las variables de
// entorno INSTITUCION_NOMBRE / INSTITUCION_NOMBRE_CORTO en el .env) y el
// nombre se actualiza en toda la aplicación — el panel, los carnets, los
// reportes en PDF y la página de inicio.
//
// Lo que SÍ seguiría dependiendo del colegio, y no es solo una variable
// de texto: los grados y grupos (ya se administran desde la base de
// datos, no están fijos en el código), y la hora límite de cada grado
// (tabla `horarios`, también editable). Lo único verdaderamente fijo en
// el código hoy es el logo del carnet — eso sí requeriría reemplazar el
// archivo de imagen.

return [
    'nombre' => env('INSTITUCION_NOMBRE', 'Institución Educativa San José'),
    'nombre_corto' => env('INSTITUCION_NOMBRE_CORTO', 'I.E. San José'),
];
