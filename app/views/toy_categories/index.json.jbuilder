json.array!(@toy_categories) do |toy_category|
  json.extract! toy_category, :id, :title
  json.url toy_category_url(toy_category, format: :json)
end
