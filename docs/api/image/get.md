# Get image

__URL__: `/images/{uuid}`

__Method__: `GET`

__URL params__: `s=[integer]` - optional; image height in pixels; possible value range - from 10 to 800

## Success reponse
__Condition__: If valid UUID is passed as a param and image exists

__Code__: `200 OK`

__Content__: content of an image

__Content type__: `image/png`

## Error response
__Condition__: If image with gived UUID does not exist

__Code__: `404 NOT FOUND`

__Content__: `{}`