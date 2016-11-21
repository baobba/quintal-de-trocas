json.array!(@stores) do |store|
  json.extract! store, :id, :name, :user_id
  json.url store_url(store, format: :json)
end
