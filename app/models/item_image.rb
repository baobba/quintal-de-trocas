class ItemImage < ActiveRecord::Base

  mount_base64_uploader :image, ItemUploader
  belongs_to :item

  scope :featured, -> { where(featured: true) }
  
end