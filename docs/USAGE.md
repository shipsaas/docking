# Document Template Management Usage

You would definitely come up with this question: "How am I going to store the template ID from other services?".

Well, it is simple, I will give you some options.

There are 2 identifiers that you can use:

- uuid
- key (unique key)

## Hardcoded ID/Key

Yes, simple & straightforward. You can go to the Console UI and create a template for your service. 

Then get the ID and put it as a `constant` in your code.

The one drawback is that, you have to change the ID if you create another template, or create more constants. I know
for some system, they won't let you deploy multiple times a day.

This method works great for those applications that follow the Trunk-based development (CI/CD best practice, multiple deployment every day).

## ENV Configuration

Yes, you can create a new ENV variable and put it there. It is not so different like the above way, but at least, change the

`.env` file then gratefully restart the server.

It also has the same drawback as the Hardcoded ID's way, if you're going to introduce new template.

## Dynamic Configuration

I think these days, most of the applications do have the dynamic configuration, where you store the configurations in the database,
and you have your own Dashboard/Console to create/update/delete that configuration.

```php
getDynamicConfig('templates.contract_template_id'); // will return the value from DB
```

## New table
Same as above way, but putting the template IDs in a dedicated table eg `templates`. 

The table's structure is just simply:

- id (generated id)
- external_template_id (template's ID from DocKing)
- name
- created_at

And that's all.

Feel free to share to the others for another cool way of yours 😉
