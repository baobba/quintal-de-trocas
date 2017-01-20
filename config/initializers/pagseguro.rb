PagSeguro.configure do |config|
  config.token       = ENV["PAGSEGURO_TOKEN"]
  config.email       = "contato@quintaldetrocas.com.br"
  config.environment = (Rails.env == "production" ? :production : :sandbox) # productionou :sandbox. O padrão é production.
  config.encoding    = "UTF-8" # ou ISO-8859-1. O padrão é UTF-8.
end