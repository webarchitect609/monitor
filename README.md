Super-lightweight monitoring via HTTP-request.
==============================================

**Be careful! This is non-stable alpha version so far!**

The library enables to monitor different metrics via HTTP-request using token in X-Monitor-Token header. 

How to use:
-----------------

1 Copy `resources/monitor-dist.php` with convenient name to web-site folder. 

2 Setup parameters following the instructions in the file. Don't forget to specify a long enough secure token! And try
not to enable your application fully. Use only minimal configuration to allow the library to collect metrics.


3 Check everything is correct. For example, using curl:

```
curl -XGET 'http://example.org/monitor.php?metric=dummy-metric' \
    -H 'X-Monitor-Token: very-long-token-to-be-placed-here!'
```

4 Setup monitoring software (for example, Zabbix) for sending a request with the token and the name of desired metric.

Enjoy beautiful data graphs! 
