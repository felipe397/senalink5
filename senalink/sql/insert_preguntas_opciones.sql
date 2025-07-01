-- Script para poblar preguntas y opciones de ejemplo

INSERT INTO preguntas (id, enunciado) VALUES
(1, '¿A qué sector productivo pertenece la empresa?'),
(2, '¿En qué áreas o procesos de su empresa considera que es necesario incorporar nuevo talento humano?'),
(3, '¿Qué áreas de conocimiento o formativas considera importantes para fortalecer su empresa?'),
(4, '¿Qué tecnologías, herramientas o procesos específicos están presentes en su operación diaria?'),
(5, '¿Qué nivel de formación considera más adecuado para su empresa?'),
(6, '¿Qué tipo de conocimientos o habilidades considera que hacen falta en su empresa?');

INSERT INTO opciones (id, id_pregunta, texto) VALUES
(1, 1, 'Sector Primario (Agrícola)'),
(2, 1, 'Sector Secundario (Industrial)'),
(3, 1, 'Sector Terciario (Servicios)'),
(4, 1, 'Sector Cuaternario (Investigación y Desarrollo)'),
(5, 2, 'Áreas de talento humano'),
(6, 2, 'Administración de personal'),
(7, 2, 'Capacitación y selección de talento'),
(8, 2, 'Formación'),
(9, 2, 'Desarrollo organizacional'),
(10, 2, 'Salud y seguridad en el trabajo'),
(11, 5, 'Auxiliar'),
(12, 5, 'Operario'),
(13, 5, 'Técnico'),
(14, 5, 'Tecnólogo');
