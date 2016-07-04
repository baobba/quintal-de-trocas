json.array!(@toy_ages) do |toy_age|
  json.extract! toy_age, :id, :title
  json.url toy_age_url(toy_age, format: :json)
end
