PagSeguro.configure do |config|
  config.token       = ENV["PAGSEGURO_TOKEN"]
  config.email       = "netto16@gmail.com"
  config.environment = :sandbox # ou :sandbox. O padrão é production.
  config.encoding    = "UTF-8" # ou ISO-8859-1. O padrão é UTF-8.
end