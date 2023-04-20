# WebHook of DocKing

Note: only available for async rendering.

After rendered the file using the async method, DocKing will automatically send a notification (webhook request) to your desired URL.

## Payload

```json
{
    "uuid": "fileUuid",
    "url": "fileUrl"
}
```
