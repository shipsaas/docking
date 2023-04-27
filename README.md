# DocKing - Document-management microservice

DocKing is a document-management microservice. Deal with the templates & render stuff just in 1 place.

You can use DocKing as a Shared-Microservice which can be used in any services.

Documentation: [DocKing](https://docking.shipsaas.tech)

## Features
- Manage your document templates 📰🧾.
- Render HTML based on your desired data for a specific template, then export it as PDF 🏃‍.
- Webhook notification after PDF rendered (for async flow) 🚀
- Built-in UI-console to manage the services (for internal use) 🔋.
- DocKing can perfectly fit for the horizontal scaling based on your needs 😉.

## Diagram of how it works

![DocKing](./docs/img/full-picture.png)

From the diagram above, DocKing is standing as a "shared-microservice".

- Billing Service can manage their bill templates and render the PDFs.
- Order Service can manage their order templates and render the PDFs.
- Contract Service can manage their contract templates and render the PDFs.
- ...

Awesome, IKR?

## DocKing uses
- PHP 8.2
- Laravel 10
- Any database (MySQL, PostgreSQL or SQLite - your choice)
  -  Personal preference: MySQL 8

### PDF Rendering Services
- Gotenberg ⭐️
- wkHTMLtoPDF ✅
- (Planned) mPDF
  - Waiting for `psr-*` updates

## Communication via services

Please check out [Restful APIs](./docs/ENDPOINTS.md)

## Development & Contributions

Please check out the [Development Guidelines](./docs)

## LICENSE

MIT LICENSE

## Contributors

ShipSaaS x Seth Phat & Contributors.
