# Apache Log Filter

This is a simple PHP script that is used for filtering Apache HTTP Server logs. They contain log entries similar to this - 

```
114.119.130.31 - - [06/Mar/2022:07:59:43 -0600] "GET /robots.txt HTTP/1.1" 403 - "-" "Mozilla/5.0 (compatible;PetalBot;+https://webmaster.petalsearch.com/site/petalbot)"
```

## Use Case

I've been monitoring the HTTP traffic on some servers and realized that the logs contained a lot of entries for IP addresses that were *known*. This PHP script compares the log entry IP address with **known** addresses and CIDR and filters out the matches. And the result is a less cluttered and easier to read log file.

## Filters

**aplogfilter.json**:
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
php run.php example.log filtered.log
```

### Example

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

A larger log file is also provided for testing, it is `large_example.log`.

**filtered.log**:
```
114.119.130.31 - - [06/Mar/2022:07:59:43 -0600] "GET /robots.txt HTTP/1.1" 403 - "-" "Mozilla/5.0 (compatible;PetalBot;+https://webmaster.petalsearch.com/site/petalbot)"
167.248.133.120 - - [06/Mar/2022:10:55:30 -0600] "GET /autodiscover/autodiscover.xml HTTP/1.1" 400 52 "-" "Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)"
185.83.144.103 - - [06/Mar/2022:12:09:43 -0600] "GET /.aws/.credentials.swp HTTP/1.1" 403 - "-" "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36"
64.71.131.244 - - [06/Mar/2022:13:22:59 -0600] "GET / HTTP/1.1" 302 - "-" "Mozilla/5.0 (compatible; ev-crawler/1.0; +https://headline.com/legal/crawler)"
```

## Error Messages

If an error occurs the script will exit and display a message on the console. 

|           **Message**           |                  **Meaning**                 |
|:-------------------------------:|:--------------------------------------------:|
| need input log file name        | The first argument is missing                |
| (file) does not exist           | The input log file does not exist            |
| need output file name           | The second argument is missing               |
| bad filter file                 | The `filter.json` file is missing or corrupt |
| cannot open for output - (file) | Could not open the output file               |
| unknown line - (log entry)      | The entry line was not recognizable          |
| error writing to (file)         | Could not write to the output file           |
| cannot open for input - (file)  | Could not open the log file                  |

Where "(file)" is a file name, and "(log entry)" is a line in the log file.

On success: "(file) has been saved".

# Possible Issues

* Large files, like several megabytes or larger may impact execution speed.
* A large quantity of IP filters may impact execution speed.

# Known Issues

This section will be updated when ever new issues are discovered, but not yet resolved.

# Future

I'm planning on adding more "filters". Which may include:

* HTTP method
* HTTP response code
* User Agent

The current IP filter *removes* entries, but the new filters may have the ability to either exclude or only include filter matches. I will also investigate using 2 or more filters *in combination*.

---
<img src="http://webexperiment.info/extcounter/mdcount.php?id=apache-log_filter">