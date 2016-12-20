PagSeguro.configure do |config|
  config.token       = "0DC556CD46854C6C85DF2938ACEF1571"
  config.email       = "contato@quintaldetrocas.com.br"
  config.environment = :sandbox # ou :sandbox. O padrão é production.
  config.encoding    = "UTF-8" # ou ISO-8859-1. O padrão é UTF-8.
end