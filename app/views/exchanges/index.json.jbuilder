json.array!(@exchanges) do |exchange|
  json.extract! exchange, :id, :item_from, :item_to, :status, :exchange_type
  json.url exchange_url(exchange, format: :json)
end
