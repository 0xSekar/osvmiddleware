UPDATE tickers INNER JOIN exchange_conversion ON tickers.exchange = exchange_conversion.name_from SET tickers.exchange = exchange_conversion.name_to
