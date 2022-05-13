# simple rest framework

A simple REST services framework to be used as a skeleton.


## Client

On client, we can declare various proxies who extend the `REST_Proxy` class. All they need to provide is the relative resource URL. A REST_Client is injected for testing or other purposes.

The rest client implements all the HTTP calls to the REST server using the correct URL and verb. It then returns the resulting data 



## Server

Server contains a central dispatcher. The dispatcher converts the HTTP verb into the appropriate function of the Resource handler. Each Resource handler has to extend the `Base_Resource` class and implement the list_all(), load(), insert(), update(), delete() functions.

Each dispatcher returns an array of an int (200, 500 or other HTTP status code) and the data to be returned to the client. See customer resource as an example.

## Simplicity


This framework has been kept simple, as it serves as proof of concept and basis of discussion. Error handling, authentication and authorization, more advanced functionality have been omitted on purpose.

