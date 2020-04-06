# Store image

__URL__: `/images`

__Method__: `POST`

__URL params__: `file=[file]` - required; image file

## Success reponse
__Condition__: If valid image is passed to a server and is succesfully saved on a storage

__Code__: `200 OK`

__Content example__:
```
{
    'data': {
        'uuid': <uuid-string>
    }
}
```

## Error response
__Condition__: If file is not provided in the body

__Code__: `422 UNPROCESSABLE ENTITY`

__Content__: `{}`

### OR
__Condition__: If file is not a valid image

__Code__: `422 UNSUPPORTED MEDIA TYPE`

__Content__: `{}`