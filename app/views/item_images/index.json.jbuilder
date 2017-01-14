json.array!(@item_images) do |item_image|
  json.extract! item_image, :id, :item_id, :image
  json.url item_image_url(item_image, format: :json)
end
