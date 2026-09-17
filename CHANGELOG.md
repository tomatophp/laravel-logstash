# 2.0.0

- Support Laravel 12 and 13 on PHP 8.2+, Monolog 3 and Guzzle 7.8+.
- Drop the unused `tomatophp/console-helpers` dependency.
- Send the Logstash-formatted record (`LogstashFormatter`) instead of the raw Monolog record, so timestamps and exceptions arrive as plain JSON.
- A failing or unreachable Logstash no longer throws: `Logstash::send()` returns `false` and the log handler never breaks the application.
- Honour the channel config (`level`, `url`, `queue`, `queue_connection`, `queue_name`) and do not overwrite an application-defined `logstash` channel.
- New config options: `level`, `queue`, `queue_connection`, `queue_name` and `timeout`.
- Real Pest test suite on Orchestra Testbench.

# V1.0.0

First release of the package
