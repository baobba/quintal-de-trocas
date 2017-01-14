json.array!(@item_ages) do |item_age|
  json.extract! item_age, :id, :title
  json.url item_age_url(item_age, format: :json)
end
