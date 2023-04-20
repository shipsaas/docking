# Endpoints of DocKing

RestFul APIs ready to use.

## [GET] v1/document-templates

Get a list of templates

## [GET] v1/document-templates/{uuid}

Get a single template detail

## [POST] v1/document-templates

Create a new template

## [PUT] v1/document-templates/{uuid}

Update a single template

## [DELETE] v1/document-templates/{uuid}

Delete a single template

## [POST] v1/document-templates/{uuid}/pdfs

Render a template with given data and returns the PDF file URL.

## [POST] v1/document-templates/{uuid}/pdfs-async

Asynchronously render a new template. 

## [POST] v1/document-files/{uuid}

Returns the rendered pdf
