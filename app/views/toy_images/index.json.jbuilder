json.array!(@toy_images) do |toy_image|
  json.extract! toy_image, :id, :toy_id, :image
  json.url toy_image_url(toy_image, format: :json)
end
