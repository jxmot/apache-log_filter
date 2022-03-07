# Apache Log Filter

This is a simple PHP script that is used for filtering Apache HTTP Server logs. They contain entries like this - 

```
114.119.130.31 - - [06/Mar/2022:07:59:43 -0600] "GET /robots.txt HTTP/1.1" 403 - "-" "Mozilla/5.0 (compatible;PetalBot;+https://webmaster.petalsearch.com/site/petalbot)"
```

## Use Case

I've been monitoring the HTTP traffic on some servers and realized that the logs contained a lot of entries for IP addresses that were *known*. This PHP script compares the log entry IP address with **known** addresses and CIDR and filters out the matches.

## Filters

**filter.json**:
```
{
    "iplist": [
        "127.0.0.1",
        "192.168.50.0/24",
        "192.168.0.27"
    ]
}
```

## Usage

```
php aplogfilter.php example.log filtered.log
```

## Example

**example.log**:
```
114.119.130.31 - - [06/Mar/2022:07:59:43 -0600] "GET /robots.txt HTTP/1.1" 403 - "-" "Mozilla/5.0 (compatible;PetalBot;+https://webmaster.petalsearch.com/site/petalbot)"
127.0.0.1 - - [06/Mar/2022:08:36:17 -0600] "GET /robots.txt HTTP/2.0" 200 167 "-" "Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)"
192.168.50.22 - - [06/Mar/2022:10:55:28 -0600] "GET / HTTP/1.1" 400 52 "-" "Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)"
167.248.133.120 - - [06/Mar/2022:10:55:30 -0600] "GET /autodiscover/autodiscover.xml HTTP/1.1" 400 52 "-" "Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)"
185.83.144.103 - - [06/Mar/2022:12:09:43 -0600] "GET /.aws/.credentials.swp HTTP/1.1" 403 - "-" "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36"
64.71.131.244 - - [06/Mar/2022:13:22:59 -0600] "GET / HTTP/1.1" 302 - "-" "Mozilla/5.0 (compatible; ev-crawler/1.0; +https://headline.com/legal/crawler)"
192.168.0.27 - - [06/Mar/2022:23:28:26 -0600] "GET / HTTP/1.1" 403 - "-" "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36"
```

**filtered.log**:
```
114.119.130.31 - - [06/Mar/2022:07:59:43 -0600] "GET /robots.txt HTTP/1.1" 403 - "-" "Mozilla/5.0 (compatible;PetalBot;+https://webmaster.petalsearch.com/site/petalbot)"
167.248.133.120 - - [06/Mar/2022:10:55:30 -0600] "GET /autodiscover/autodiscover.xml HTTP/1.1" 400 52 "-" "Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)"
185.83.144.103 - - [06/Mar/2022:12:09:43 -0600] "GET /.aws/.credentials.swp HTTP/1.1" 403 - "-" "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36"
64.71.131.244 - - [06/Mar/2022:13:22:59 -0600] "GET / HTTP/1.1" 302 - "-" "Mozilla/5.0 (compatible; ev-crawler/1.0; +https://headline.com/legal/crawler)"
```

