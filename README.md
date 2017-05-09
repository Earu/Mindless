# Mindless

You must work under the public folder, and it is strongly recommandended you browse through the files that already exists.
You must not delete or change the name of any folder / files that already exist unless you are the author of those.


The different syntaxes this framework implements are as following:

### PHP function calls
{{ yourphpfunctionhere(); }} 

It allows you to call php functions of the controller withing the .html file.

### Imports
There are two types of imports

1 - Service

The services you create in /public/service/ can be imported within an .html file by doing:

[import Service[servicename]]

2 - Templates

Templates are static parts of .html files, they can be imported within .html by doing:

[import Template[templatename]]


#### To keep in mind

This framework was developed by me and a friend / co-worker to achieve a specific need, you may encounter troubles using it, so I wouldnt recommend using it even if it can be really useful to set up small websites that dont require huge framework control such as symphony or zend.
