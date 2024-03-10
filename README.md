# Challenge PHP
A continuación se describe el desarrollo de un API REST con autenticación OAuth2.0, con una integración de la API de [GHIFY](https://developers.giphy.com/docs/api/#quick-start-guide).

## Diagrama de Casos de Uso
![Diagrama de Casos de Uso](documentos/Diagrama_Casos_Uso.png)

## Diagrama de Secuencia - CU: Login
![Diagrama de Secuencia - CU: Login](documentos/Diagrama_Secuencia_Login.png)

## Diagrama de Secuencia - CU: Buscar GIFs
![Diagrama de Secuencia - CU: Buscar GIFs](documentos/Diagrama_Secuencia_Buscar_Gifs.png)

## Diagrama de Secuencia - CU: Buscar GIF por ID
![Diagrama de Secuencia - CU: Buscar GIF por ID](documentos/Diagrama_Secuencia_Buscar_Gif_por_ID.png)

## Diagrama de Secuencia - CU: Guardar GIF Favorito
![Diagrama de Secuencia - CU: Guardar GIF Favorito](documentos/Diagrama_Secuencia_Guardar_Gif_Favorito.png)

## Diagrama de Entidad Relación
![Diagrama de Entidad Relación](documentos/DER.png)

## Colección de Postman.

- **Nombre**: API - Challenge
- **Descripción**: contiene servicios para:
- - Solicitar Login, se integra el script de automatización para que el valor del Token resultante, se almacene en el environment.
- - Buscar Gifs por texto (una frase o término).
- - Buscar un Gif por su ID ([el cual es un string](https://developers.giphy.com/docs/api/endpoint#get-gif-by-id)).
- - Guardar un Gif como favorito, para el usuario autenticado.
- Enlace para la descarga completa de la colección [aquí](documentos/api-challenge.postman_collection.json).



## Tecnologías aplicadas:
- PHP v8.3.3
- Laravel Framework v10
- MySQL
- UML
