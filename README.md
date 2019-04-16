# Mindless

You must work under the public folder.

The different syntaxes this framework implements are as following:

### PHP function calls
```{{ yourphpfunctionhere(); }} ```
The above allows you to call php functions of the controller withing the .html file.

### Imports
There are two types of imports:

- Service

The services you create in /public/service/ can be imported within an .html file by doing:
```
[import Service[servicename]]
```

- Templates

Templates are static parts of .html files, they can be imported within .html by doing:
```
[import Template[templatename]]
```

### Routes,Templates,Permissions

- Routes
Routes can be configured inside the **RouteProvider.php** file, you can assign an url to a controller and a view

- Templates
To use templates you need to give them names, this can be done in **TemplateProvider.php**

- Permissions
Permissions work accordingly to the config in **Permissions.php**, and relatively to account types, you can add account types in **\_conf/Constants.php** by adding a new value inside $ACCOUNT_TYPE constant.

### Secured forms
To create a secured form you must create html elements like such:

```
[HTMLELEMENT]{
"name" : "htmlelement",
"attribute" : "value"
}
```

Attributes depend on what html element you picked.
An html element must always have a name attribute.

Mindless adds some new attributes as well:

**[input]minChar(int)** 

*checks if the string inputed has at least a specific number of chars*

**[input]maxChar(int)** 

*checks if the string inputed has less than a specific number of chars*

**[all]parent(string)** 

*set the parent of the html element you created to the one with the specified name*

**[form]handle(string)** 

*gets the name of the php function of the controller object to be executed when sending the form*

**[all-form]required(void)** 

*makes the html element required to complete the form both client and server side ( "required" : "")*

**[all-form]HTML(string)** 

*the html content to be included between the tags of the html element*

#### To keep in mind
This framework was developed by me and a friend / co-worker to achieve a specific need, you may encounter troubles using it, so I wouldnt recommend using it even if it can be really useful to set up small websites that dont require huge framework control such as symphony or zend.
