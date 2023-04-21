# DocKing - Document-management microservice

DocKing is a document-management microservice. Deal with the templates & render stuff just in 1 place.

You can use DocKing as a Shared-Microservice which can be used in any services.

## Features
- Manage your document templates.
- Render HTML based on your desired data for a specific template, then export it as PDF.
- Webhook notification after PDF rendered (for async flow).
- Built-in UI-console to manage the services (for internal use).

## Diagram of how it works

![DocKing](./docs/img/full-picture.png)

## DocKing uses
- PHP 8.2
- Laravel 10
- MySQL/PostgreSQL (your desire)

### PDF Rendering Services
- Gotenberg
- wkHTMLtoPDF
- mpdf

## Communication via services

- Restful APIs

## LICENSE

MIT LICENSE

## Contributors

ShipSaaS x Seth Phat & Contributors.
