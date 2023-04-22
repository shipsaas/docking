# WebHook of DocKing

Note: only available for async rendering.

After rendered the file using the async method, DocKing will automatically send a notification (webhook request) to your desired URL.

## Method
POST

## Payload's Structure

```json
{
    "uuid": "fileUuid",
    "url": "fileUrl"
}
```

## Notes
Prefer using Internal IP/URL. 

If you are going to use an External URL (eg your app domain), then you need to ensure DocKing instance has the outbound internet
connection.
