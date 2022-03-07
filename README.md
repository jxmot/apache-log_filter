# Apache Log Filter

This is a simple PHP script that is used for filtering Apache HTTP Server logs. They contain entries like this - 

```
114.119.130.31 - - [06/Mar/2022:07:59:43 -0600] "GET /robots.txt HTTP/1.1" 403 - "-" "Mozilla/5.0 (compatible;PetalBot;+https://webmaster.petalsearch.com/site/petalbot)"
```

## Use Case

I've been monitoring the HTTP traffic on some servers and realized that the logs contained a lot of entries for IP addresses that were *known*. This PHP script compares the log entry IP address with **known** addresses and CIDR and filters out the matches.

