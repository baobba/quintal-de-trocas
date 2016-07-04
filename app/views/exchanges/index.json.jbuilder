json.array!(@exchanges) do |exchange|
  json.extract! exchange, :id, :toy_from, :toy_to, :status, :exchange_type
  json.url exchange_url(exchange, format: :json)
end
